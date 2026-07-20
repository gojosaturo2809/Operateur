<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table = 'types_operation';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $allowedFields = [
        'nom'
    ];

    /**
     * Retourner tous les types d'opérations
     */
    public function getAll()
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    /**
     * Ajouter un type d'opération
     */
    public function ajouter($nom)
    {
        return $this->insert([
            'nom' => $nom
        ]);
    }

    /**
     * Modifier un type d'opération
     */
    public function modifier($id, $nom)
    {
        return $this->update($id, [
            'nom' => $nom
        ]);
    }

    /**
     * Supprimer un type d'opération
     */
    public function supprimer($id)
    {
        return $this->delete($id);
    }

    /**
     * Rechercher un type par son nom
     */
    public function getByNom($nom)
    {
        return $this->where('nom', $nom)->first();
    }
}