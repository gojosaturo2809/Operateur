<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table         = 'operateurs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nom_utilisateur', 'mot_de_passe_hash'];

    protected $useTimestamps  = false; // date_creation géré par DEFAULT

    // ── Authentification ──────────────────────────────────────────────────────

    /**
     * Vérifie les identifiants et retourne l'opérateur si valide, null sinon.
     *
     * @param string $nomUtilisateur
     * @param string $motDePasse     Mot de passe en clair
     * @return array|null
     */
    public function authenticate(string $nomUtilisateur, string $motDePasse): ?array
    {
        $operateur = $this->where('nom_utilisateur', trim($nomUtilisateur))->first();

        if (!$operateur) {
            return null;
        }

        if (!password_verify($motDePasse, $operateur['mot_de_passe_hash'])) {
            return null;
        }

        return $operateur;
    }

    
}
