<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardAdminModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

   
    
    public function getTotalClients(): int
    {
        return (int) $this->db->table('clients')->countAllResults();
    }

    /**
     * Nombre total d'opérations toutes catégories confondues.
     */
    public function getTotalOperations(): int
    {
        return (int) $this->db->table('operations')->countAllResults();
    }

    /**
     * Gains totaux de l'opérateur (frais sur retraits + transferts).
     */
    public function getTotalGains(): float
    {
        $row = $this->db
            ->table('operations o')
            ->select('SUM(o.frais_applique) AS total')
            ->join('types_operation t', 'o.id_type_operation = t.id')
            ->whereIn('t.nom', ['retrait', 'transfert'])
            ->get()->getRowArray();

        return (float) ($row['total'] ?? 0);
    }

    /**
     * Volume financier total (somme des montants de toutes les opérations).
     */
    public function getVolumeFinancierTotal(): float
    {
        $row = $this->db
            ->table('operations')
            ->select('SUM(montant) AS total')
            ->get()->getRowArray();

        return (float) ($row['total'] ?? 0);
    }

    // ─── Données pour graphiques ──────────────────────────────────────────────

    /**
     * Gains et volume par type d'opération (depuis la vue vue_situation_gains).
     * Utilisé pour le graphique en barres.
     *
     * @return array  [['type_operation'=>..., 'volume_transactions'=>..., 'volume_financier'=>..., 'total_gains'=>...], ...]
     */
    public function getGainsByType(): array
    {
        return $this->db
            ->table('vue_situation_gains')
            ->get()->getResultArray();
    }

    /**
     * Nombre d'opérations par type, toutes opérations.
     * Utilisé pour le graphique en donut.
     *
     * @return array  [['nom'=>'depot', 'nb'=>42], ...]
     */
    public function getOperationsParType(): array
    {
        return $this->db
            ->table('operations o')
            ->select('t.nom, COUNT(o.id) AS nb')
            ->join('types_operation t', 'o.id_type_operation = t.id')
            ->groupBy('t.nom')
            ->get()->getResultArray();
    }

    /**
     * Évolution mensuelle des opérations sur les 12 derniers mois.
     * Utilisé pour le graphique en courbe.
     *
     * @return array  [['mois'=>'2025-01', 'nb'=>15, 'gains'=>8500], ...]
     */
    public function getOperationsParMois(): array
    {
        return $this->db->query("
            SELECT
                strftime('%Y-%m', o.date_operation) AS mois,
                COUNT(o.id)                         AS nb,
                SUM(CASE WHEN t.nom IN ('retrait','transfert')
                         THEN o.frais_applique ELSE 0 END) AS gains
            FROM operations o
            JOIN types_operation t ON o.id_type_operation = t.id
            GROUP BY mois
            ORDER BY mois
            LIMIT 12
        ")->getResultArray();
    }

    /**
     * Répartition du volume financier par type d'opération.
     *
     * @return array  [['nom'=>'depot', 'volume'=>120000], ...]
     */
    public function getVolumeParType(): array
    {
        return $this->db
            ->table('operations o')
            ->select('t.nom, SUM(o.montant) AS volume')
            ->join('types_operation t', 'o.id_type_operation = t.id')
            ->groupBy('t.nom')
            ->get()->getResultArray();
    }

    // ─── Dernières opérations ─────────────────────────────────────────────────

    /**
     * Les N dernières opérations avec leur type et le numéro client.
     *
     * @param  int   $limit
     * @return array
     */
    public function getDernieresOperations(int $limit = 8): array
    {
        return $this->db
            ->table('operations o')
            ->select('o.id, c.numero_telephone, t.nom AS type_operation,
                      o.montant, o.frais_applique, o.numero_destinataire,
                      o.date_operation')
            ->join('clients c',          'o.id_client = c.id')
            ->join('types_operation t',  'o.id_type_operation = t.id')
            ->orderBy('o.date_operation', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }
}
