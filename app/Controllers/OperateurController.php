<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\PrefixeModel;

class OperateurController extends BaseController
{
    private OperateurModel $operateurModel;
    private PrefixeModel $prefixeModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
        $this->prefixeModel   = new PrefixeModel();
    }

    /**
     * Page principale de configuration des opérateurs et préfixes (V2)
     */
    public function configOperateurs()
    {
        data = [
            'title'      => 'Configuration Multi-Opérateurs',
            'operateurs' => $this->operateurModel->findAll(),
            'prefixes'   => $this->prefixeModel->getPrefixesAvecOperateur()
        ];

        return view('operateur/config_operateurs', data);
    }

    /**
     * Action : Ajouter un nouvel opérateur tiers avec sa commission
     */
    public function storeOperateur()
    {
        $rules = [
            'nom'                  => 'required|min_length[2]|is_unique[operateurs.nom]',
            'commission_inter_pct' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('erreur', 'Données invalides ou opérateur déjà existant.');
        }

        $this->operateurModel->save([
            'nom'                  => $this->request->getPost('nom'),
            'est_principal'        => false, // C'est forcément un autre opérateur
            'commission_inter_pct' => $this->request->getPost('commission_inter_pct')
        ]);

        return redirect()->to('operateur/config-operateurs')->with('succes', 'Nouvel opérateur configuré avec succès.');
    }

    /**
     * Action : Associer un préfixe à un opérateur
     */
    public function storePrefixe()
    {
        $rules = [
            'prefixe'      => 'required|max_length[5]|is_unique[prefixes.prefixe]',
            'id_operateur' => 'required|is_not_unique[operateurs.id]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('erreur', 'Préfixe déjà configuré ou opérateur inconnu.');
        }

        $this->prefixeModel->save([
            'prefixe'      => $this->request->getPost('prefixe'),
            'id_operateur' => $this->request->getPost('id_operateur')
        ]);

        return redirect()->to('operateur/config-operateurs')->with('succes', 'Préfixe enregistré et lié avec succès.');
    }
}