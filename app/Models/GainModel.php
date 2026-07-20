<?php

namespace App\Models;

use CodeIgniter\Model;

class GainModel extends Model
{
    protected $table      = 'vue_situation_gains';
    protected $primaryKey = 'type_operation';
    protected $returnType = 'array';

    protected $allowedFields = [
        'type_operation',
        'volume_transactions',
        'volume_financier',
        'total_gains',
    ];

    // ── Vue globale (rétrocompatibilité) ──────────────────────────────────────

    public function getSituationGains(): array
    {
        return $this->findAll();
    }

    public function getGainTotal(): float
    {
        $result = $this->selectSum('total_gains', 'gain_total')->first();
        return (float) ($result['gain_total'] ?? 0);
    }

    public function getGainParType(string $type): ?array
    {
        return $this->where('type_operation', $type)->first();
    }

    // ── Gains Réseau Local ────────────────────────────────────────────────────
    // Retraits (toujours locaux) + transferts dont le destinataire
    // possède un préfixe appartenant à l'opérateur PRINCIPAL.

    public function getGainsLocal(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                t.nom                         AS type_operation,
                COUNT(o.id)                   AS volume_transactions,
                COALESCE(SUM(o.montant), 0)   AS volume_financier,
                COALESCE(SUM(o.frais_applique), 0) AS total_gains
            FROM operations o
            JOIN types_operation t ON o.id_type_operation = t.id
            WHERE
                t.nom = 'retrait'
                OR (
                    t.nom = 'transfert'
                    AND (
                        o.numero_destinataire IS NULL
                        OR substr(o.numero_destinataire, 1, 3) IN (
                            SELECT p.prefixe
                            FROM prefixes p
                            JOIN operateurs op ON p.id_operateur = op.id
                            WHERE op.est_principal = 1
                        )
                    )
                )
            GROUP BY t.nom
            ORDER BY t.nom
        ")->getResultArray();
    }

    public function getTotalGainsLocal(): float
    {
        $db = \Config\Database::connect();

        $row = $db->query("
            SELECT COALESCE(SUM(o.frais_applique), 0) AS total
            FROM operations o
            JOIN types_operation t ON o.id_type_operation = t.id
            WHERE
                t.nom = 'retrait'
                OR (
                    t.nom = 'transfert'
                    AND (
                        o.numero_destinataire IS NULL
                        OR substr(o.numero_destinataire, 1, 3) IN (
                            SELECT p.prefixe
                            FROM prefixes p
                            JOIN operateurs op ON p.id_operateur = op.id
                            WHERE op.est_principal = 1
                        )
                    )
                )
        ")->getRowArray();

        return (float) ($row['total'] ?? 0);
    }

    // ── Commissions Inter-Opérateurs ──────────────────────────────────────────
    // Transferts dont le destinataire est sur un réseau TIERS
    // (préfixe absent des préfixes de l'opérateur principal).

    public function getGainsInter(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                op.nom                            AS operateur_tiers,
                op.commission_inter_pct,
                COUNT(o.id)                       AS volume_transactions,
                COALESCE(SUM(o.montant), 0)       AS volume_financier,
                COALESCE(SUM(o.frais_applique), 0) AS total_gains
            FROM operations o
            JOIN types_operation t   ON o.id_type_operation = t.id
            JOIN prefixes p          ON p.prefixe = substr(o.numero_destinataire, 1, 3)
            JOIN operateurs op       ON p.id_operateur = op.id
            WHERE
                t.nom = 'transfert'
                AND o.numero_destinataire IS NOT NULL
                AND op.est_principal = 0
            GROUP BY op.id
            ORDER BY total_gains DESC
        ")->getResultArray();
    }

    /**
     * Transferts vers des préfixes totalement inconnus (ni local, ni tiers enregistré).
     */
    public function getGainsInterInconnus(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                COUNT(o.id)                       AS volume_transactions,
                COALESCE(SUM(o.montant), 0)       AS volume_financier,
                COALESCE(SUM(o.frais_applique), 0) AS total_gains
            FROM operations o
            JOIN types_operation t ON o.id_type_operation = t.id
            WHERE
                t.nom = 'transfert'
                AND o.numero_destinataire IS NOT NULL
                AND substr(o.numero_destinataire, 1, 3) NOT IN (
                    SELECT prefixe FROM prefixes
                )
        ")->getRowArray();
    }

    public function getTotalGainsInter(): float
    {
        $db = \Config\Database::connect();

        $row = $db->query("
            SELECT COALESCE(SUM(o.frais_applique), 0) AS total
            FROM operations o
            JOIN types_operation t ON o.id_type_operation = t.id
            WHERE
                t.nom = 'transfert'
                AND o.numero_destinataire IS NOT NULL
                AND substr(o.numero_destinataire, 1, 3) NOT IN (
                    SELECT p.prefixe
                    FROM prefixes p
                    JOIN operateurs op ON p.id_operateur = op.id
                    WHERE op.est_principal = 1
                )
        ")->getRowArray();

        return (float) ($row['total'] ?? 0);
    }
}
