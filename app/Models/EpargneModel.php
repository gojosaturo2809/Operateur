<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table = 'epargne';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_client','val_epargne','date_epargne'];


}