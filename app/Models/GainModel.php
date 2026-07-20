<?php

namespace App\Models;

use CodeIgniter\Model;

class GainModel extends Model
{
    protected $table = 'vue_situation_gains';
    protected $primaryKey = 'type_operation';

    protected $returnType = 'array';
    protected $allowedFields = [
        'type_operation',
        'volume_transactions',
        'volume_financier',
        'total_gains'
    ];

  
    public function getSituationGains()
    {
        return $this->findAll();
    }

    
    public function getGainTotal()
    {
        $result = $this->selectSum('total_gains', 'gain_total')
                       ->first();

        return $result['gain_total'] ?? 0;
    }

   
    public function getGainParType(string $type)
    {
        return $this->where('type_operation', $type)->first();
    }
}