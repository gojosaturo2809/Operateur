<?php

namespace App\Controllers;

use App\Models\GainModel;
use App\Models\ClientModel;
use App\Models\TypeOperationModel;

class OperateurController extends BaseController
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    

    public function index(): string
    {
        $gainModel = new GainModel();

        return view('operateur/gains', [
            'title'         => 'Situation des gains',
            'pageTitle'     => 'Situation des gains',
            'sidebar'       => 'sidebar_operateur',
            'situationGains'=> $gainModel->getSituationGains(),
            'gainTotal'     => $gainModel->getGainTotal(),
        ]);
    }

   

    public function clients(): string
    {
        $clients = $this->db->table('clients')
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return view('operateur/clients', [
            'title'     => 'Comptes clients',
            'pageTitle' => 'Comptes clients',
            'sidebar'   => 'sidebar_operateur',
            'clients'   => $clients,
        ]);
    }

    
}
