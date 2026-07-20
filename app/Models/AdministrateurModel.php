<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modèle d'authentification admin — table `administrateurs`
 */
class AdministrateurModel extends Model
{
    protected $table      = 'administrateurs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['nom_utilisateur', 'mot_de_passe_hash'];

    // ── Authentification ──────────────────────────────────────────────────────

    public function authenticate(string $nomUtilisateur, string $motDePasse): ?array
    {
        $admin = $this->where('nom_utilisateur', trim($nomUtilisateur))->first();

        if (!$admin || !password_verify($motDePasse, $admin['mot_de_passe_hash'])) {
            return null;
        }

        return $admin;
    }

    public function creer(string $nomUtilisateur, string $motDePasse): int|false
    {
        return $this->insert([
            'nom_utilisateur'   => trim($nomUtilisateur),
            'mot_de_passe_hash' => password_hash($motDePasse, PASSWORD_BCRYPT),
        ]);
    }
}
