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
    return $this->db
        ->table('vue_gains_local')
        ->get()
        ->getResultArray();
}

   public function getTotalGainsLocal(): float
{
    $row = $this->db
        ->table('vue_gains_local')
        ->selectSum('total_gains', 'total')
        ->get()
        ->getRowArray();

    return (float) ($row['total'] ?? 0);
}
    // ── Commissions Inter-Opérateurs ──────────────────────────────────────────
    // Transferts dont le destinataire est sur un réseau TIERS
    // (préfixe absent des préfixes de l'opérateur principal).

   public function getGainsInter(): array
{
    return $this->db
        ->table('vue_gains_inter')
        ->orderBy('total_gains', 'DESC')
        ->get()
        ->getResultArray();
}

    /**
     * Transferts vers des préfixes totalement inconnus (ni local, ni tiers enregistré).
     */
   public function getGainsInterInconnus(): array
{
    return $this->db
        ->table('vue_gains_inter_inconnus')
        ->get()
        ->getRowArray();
}
   public function getTotalGainsInter(): float
{
    $db = \Config\Database::connect();

    $connus = $db->table('vue_gains_inter')
                 ->selectSum('total_gains', 'total')
                 ->get()
                 ->getRowArray();

    $inconnus = $db->table('vue_gains_inter_inconnus')
                   ->get()
                   ->getRowArray();

    return (float)($connus['total'] ?? 0)
         + (float)($inconnus['total_gains'] ?? 0);
}


   public function getCompensationParOperateur(): array
{
    return $this->db
        ->table('vue_compensation_operateurs')
        ->orderBy('commission_a_reverser', 'DESC')
        ->get()
        ->getResultArray();
}

public function getTotalCompensation(): float
{
    $row = $this->db
        ->table('vue_compensation_operateurs')
        ->selectSum('commission_a_reverser', 'total')
        ->get()
        ->getRowArray();

    return (float)($row['total'] ?? 0);
}

public function getTotalMontantTransfere(): float
{
    $row = $this->db
        ->table('vue_compensation_operateurs')
        ->selectSum('montant_transfere', 'total')
        ->get()
        ->getRowArray();

    return (float)($row['total'] ?? 0);
}

public function getTotalFraisPercus(): float
{
    $row = $this->db
        ->table('vue_compensation_operateurs')
        ->selectSum('frais_percus', 'total')
        ->get()
        ->getRowArray();

    return (float)($row['total'] ?? 0);
}

public function getTotalTransferts(): int
{
    $row = $this->db
        ->table('vue_compensation_operateurs')
        ->selectSum('nb_transferts', 'total')
        ->get()
        ->getRowArray();

    return (int)($row['total'] ?? 0);
}

public function getNombreOperateurs(): int
{
    return $this->db
        ->table('vue_compensation_operateurs')
        ->countAllResults();
}

}
