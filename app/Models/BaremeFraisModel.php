<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'bareme_frais';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_type_operation', 'montant_min', 'montant_max', 'frais'];

    /**
     * Récupère les barèmes avec le nom complet du type d'opération associé
     */
    public function getBaremesWithTypeName()
    {
        return $this->select('bareme_frais.*, types_operation.nom as type_nom')
                    ->join('types_operation', 'types_operation.id = bareme_frais.id_type_operation')
                    ->orderBy('bareme_frais.id_type_operation', 'ASC')
                    ->orderBy('bareme_frais.montant_min', 'ASC')
                    ->findAll();
    }
}