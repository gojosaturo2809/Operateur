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
    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    /**
     * Retourner un type par son id
     */
    public function getById($id)
    {
        // Cast explicite en entier pour sécuriser la clé primaire
        return $this->find((int) $id);
    }

    /**
     * Ajouter un type d'opération
     */
    public function ajouter($nom)
    {
        // Cast explicite en chaîne de caractères
        return $this->insert([
            'nom' => trim((string) $nom)
        ]);
    }

    /**
     * Modifier un type d'opération
     */
    public function modifier($id, $nom)
    {
        // Sécurisation des deux variables ($id en int, $nom en string)
        return $this->update((int) $id, [
            'nom' => trim((string) $nom))
        ]);
    }

    /**
     * Supprimer un type d'opération
     */
    public function supprimer($id)
    {
        // Cast explicite en entier avant suppression
        return $this->delete((int) $id);
    }

    /**
     * Rechercher un type par son nom
     */
    public function getByNom($nom)
    {
        // Cast en string pour la clause WHERE
        return $this->where('nom', (string) $nom)->first();
    }
}