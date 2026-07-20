<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\ClientModel;

class AuthController extends BaseController
{
    public function login()
    {
        $session = session();

        if ($this->request->is('post')) {
            // Adaptation avec le 'name' de votre template (numero_telephone)
            $telephone = (string) $this->request->getPost('numero_telephone');

            $prefixeModel = new PrefixeModel();
            $clientModel = new ClientModel();

            // Vérification du préfixe
            if (!$prefixeModel->validerPrefixe($telephone)) {
                // Adaptation avec le flashdata de votre template (erreur)
                return redirect()->back()->with('erreur', 'Numéro invalide : préfixe non autorisé.');
            }

            // Recherche ou création du client
            $client = $clientModel->getByTelephone($telephone);

            if (!$client) {
                $nouveauId = $clientModel->ajouter($telephone);
                $client = $clientModel->find($nouveauId);
            }

            // Stockage dans la session
            $session->set([
                'isLoggedIn' => true,
                'client_id'  => (int) $client['id'],
                'telephone'  => (string) $client['numero_telephone']
            ]);

            return redirect()->to('/client/dashboard');
        }

        return view('client/login');
    }
}