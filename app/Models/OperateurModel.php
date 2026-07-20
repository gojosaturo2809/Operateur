<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modèle métier — table `operateurs`
 * Représente les opérateurs télécom (local et tiers).
 */
class OperateurModel extends Model
{
    protected $table      = 'operateurs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['nom', 'est_principal', 'commission_inter_pct'];

    // ── Requêtes métier ───────────────────────────────────────────────────────

    /**
     * Retourne l'opérateur principal (notre réseau).
     */
    public function getOperateurPrincipal(): ?array
    {
        return $this->where('est_principal', 1)->first();
    }

    /**
     * Retourne tous les opérateurs tiers (autres réseaux).
     */
    public function getOperateursTiers(): array
    {
        return $this->where('est_principal', 0)->findAll();
    }

    /**
     * Retourne tous les opérateurs avec leurs préfixes associés.
     */
    public function getTousAvecPrefixes(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                op.id,
                op.nom,
                op.est_principal,
                op.commission_inter_pct,
                GROUP_CONCAT(p.prefixe, ', ') AS prefixes
            FROM operateurs op
            LEFT JOIN prefixes p ON p.id_operateur = op.id
            GROUP BY op.id
            ORDER BY op.est_principal DESC, op.nom ASC
        ")->getResultArray();
    }
}
