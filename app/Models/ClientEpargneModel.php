<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientEpargneModel extends Model
{
    protected $table = 'pct_epargne';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_client',' epargne_pct'];


}