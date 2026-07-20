<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateurs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'est_principal', 'commission_inter_pct'];
    protected $returnType       = 'array';

    /**
     * Récupère uniquement les opérateurs tiers (autres réseaux)
     */
    public function getOperateursTiers()
    {
        return $this->where('est_principal', false)->findAll();
    }
}