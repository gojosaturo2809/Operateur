<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['prefixe', 'id_operateur']; // id_operateur ajouté pour la V2
    protected $returnType       = 'array';

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

        /**
     * Récupère tous les préfixes avec le nom de leur opérateur respectif
     */
    public function getPrefixesAvecOperateur()
    {
        return $this->select('prefixes.*, operateurs.nom as operateur_nom, operateurs.est_principal')
                    ->join('operateurs', 'operateurs.id = prefixes.id_operateur')
                    ->orderBy('prefixes.prefixe', 'ASC')
                    ->findAll();
    }
}