<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numero_telephone'];

    /**
     * Recherche un client par son numéro
     */
    public function getByTelephone(string $telephone): ?array
    {
        return $this->where('numero_telephone', trim($telephone))->first();
    }

    /**
     * Inscription automatique (Insertion) d'un nouveau client
     */
    public function ajouter(string $telephone): int
    {
        $id = $this->insert([
            'numero_telephone' => trim($telephone)
        ]);
        return (int) $id;
    }
}