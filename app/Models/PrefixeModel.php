<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['prefixe'];

    /**
     * Vérifie si le numéro commence par un préfixe valide de l'opérateur
     */
    public function validerPrefixe(string $telephone): bool
    {
        $phoneClean = trim($telephone);
        // On extrait les 3 premiers chiffres
        $extrait = (string) substr($phoneClean, 0, 3);
        
        $result = $this->where('prefixe', $extrait)->first();
        return $result !== null;
    }
}