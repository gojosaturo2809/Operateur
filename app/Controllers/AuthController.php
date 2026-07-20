<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\ClientModel;
use App\Models\OperateurModel;

class AuthController extends BaseController
{
    
    public function login(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        // Déjà connecté → redirection
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('client/dashboard'));
        }

        if ($this->request->is('post')) {
            $telephone = trim((string) $this->request->getPost('numero_telephone'));

            $prefixeModel = new PrefixeModel();
            $clientModel  = new ClientModel();

            // Validation du préfixe
            if (!$prefixeModel->validerPrefixe($telephone)) {
                return redirect()->back()
                    ->with('erreur', 'Numéro invalide : préfixe non autorisé.');
            }

            // Recherche ou création automatique du client
            $client = $clientModel->getByTelephone($telephone);

            if (!$client) {
                $nouveauId = $clientModel->ajouter($telephone);
                $client    = $clientModel->find($nouveauId);
            }

            session()->set([
                'isLoggedIn' => true,
                'role'       => 'client',
                'client_id'  => (int) $client['id'],
                'telephone'  => (string) $client['numero_telephone'],
            ]);

            return redirect()->to(base_url('client/dashboard'));
        }

        return view('client/login');
    }

    
    public function loginOperateur(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        
        if (session()->get('operateur_id')) {
            return redirect()->to(base_url('operateur/dashboard'));
        }

        if ($this->request->is('post')) {
            $nomUtilisateur = trim((string) $this->request->getPost('nom_utilisateur'));
            $motDePasse     = (string) $this->request->getPost('mot_de_passe');

            if (empty($nomUtilisateur) || empty($motDePasse)) {
                return redirect()->back()
                    ->withInput()
                    ->with('erreur', 'Veuillez remplir tous les champs.');
            }

            $operateurModel = new OperateurModel();
            $operateur      = $operateurModel->authenticate($nomUtilisateur, $motDePasse);

            if (!$operateur) {
                
                sleep(1);
                return redirect()->back()
                    ->withInput()
                    ->with('erreur', 'Identifiants incorrects. Veuillez réessayer.');
            }

            session()->set([
                'operateur_id'  => (int)    $operateur['id'],
                'operateur_nom' => (string) $operateur['nom_utilisateur'],
                'role'          => 'operateur',
            ]);

            return redirect()->to(base_url('operateur/dashboard'));
        }

        return view('operateur/login');
    }

 
    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        $role = session()->get('role');
        session()->destroy();

        if ($role === 'operateur') {
            return redirect()->to(base_url('operateur/login'))
                ->with('success', 'Vous avez été déconnecté.');
        }

        return redirect()->to(base_url('login'));
    }
}
