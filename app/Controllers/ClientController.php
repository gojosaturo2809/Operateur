<?php

namespace App\Controllers;

use App\Models\OperationModel;
use App\Models\PrefixeModel;

class ClientController extends BaseController
{
    protected $operationModel;
    protected $session;

    public function __valueSession()
    {
        $this->session = session();
        $this->operationModel = new OperationModel();
    }

    // Protection globale constructeur équivalent CI4
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = session();
        $this->operationModel = new OperationModel();
    }

    private function checkAuth()
    {
        return $this->session->get('isLoggedIn');
    }

    public function index()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $id_client = $this->session->get('client_id');
        $telephone = $this->session->get('telephone');

        $data = [
            'title'     => 'Mon Tableau de Bord',
            'telephone' => $telephone,
            'solde'     => $this->operationModel->calculateSolde($id_client, $telephone)
        ];

        return view('client/dashboard', $data);
    }

    public function depot()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');
        return view('client/depot', ['title' => 'Faire un Dépôt']);
    }

    public function storeDepot()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $montant = (float)$this->request->getPost('montant');
        if ($montant <= 0) {
            return redirect()->back()->with('erreur', 'Le montant doit être supérieur à 0.');
        }

        $this->operationModel->save([
            'id_client'          => $this->session->get('client_id'),
            'id_type_operation'  => 1, // Dépôt
            'numero_destinataire'=> null,
            'montant'            => $montant,
            'frais_applique'     => 0.0
        ]);

        return redirect()->to('/client/dashboard')->with('succes', 'Dépôt effectué avec succès !');
    }

    public function retrait()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');
        
        // Charger le barème pour le script JS de calcul en temps réel
        $db = db_connect();
        $baremes = $db->query("SELECT montant_min, montant_max, frais FROM bareme_frais WHERE id_type_operation = 2")->getResultArray();

        return view('client/retrait', [
            'title'   => 'Faire un Retrait',
            'baremes' => json_encode($baremes)
        ]);
    }

    public function storeRetrait()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $id_client = $this->session->get('client_id');
        $telephone = $this->session->get('telephone');
        $montant   = (float)$this->request->getPost('montant');

        $frais = $this->operationModel->getFraisApplicable(2, $montant); // 2 = Retrait
        if ($frais === null) {
            return redirect()->back()->with('erreur', 'Ce montant ne correspond à aucun barème autorisé.');
        }

        $soldeActuel = $this->operationModel->calculateSolde($id_client, $telephone);
        if ($soldeActuel < ($montant + $frais)) {
            return redirect()->back()->with('erreur', 'Provision insuffisante (Montant + Frais non couverts).');
        }

        $this->operationModel->save([
            'id_client'          => $id_client,
            'id_type_operation'  => 2,
            'numero_destinataire'=> null,
            'montant'            => $montant,
            'frais_applique'     => $frais
        ]);

        return redirect()->to('/client/dashboard')->with('succes', 'Retrait validé avec succès !');
    }

    public function transfert()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $db = db_connect();
        $baremes = $db->query("SELECT montant_min, montant_max, frais FROM bareme_frais WHERE id_type_operation = 3")->getResultArray();

        return view('client/transfert', [
            'title'   => 'Faire un Transfert',
            'baremes' => json_encode($baremes)
        ]);
    }

    public function storeTransfert()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $id_client    = $this->session->get('client_id');
        $telephone    = $this->session->get('telephone');
        $destinataire = (string)$this->request->getPost('numero_destinataire');
        $montant      = (float)$this->request->getPost('montant');

        if ($destinataire === $telephone) {
            return redirect()->back()->with('erreur', 'Opération invalide : impossible de s\'envoyer un transfert.');
        }

        // Validation du préfixe destinataire
        $prefixeModel = new PrefixeModel();
        if (!$prefixeModel->validerPrefixe($destinataire)) {
            return redirect()->back()->with('erreur', 'Numéro destinataire invalide (Préfixe non autorisé).');
        }

        $frais = $this->operationModel->getFraisApplicable(3, $montant); // 3 = Transfert
        if ($frais === null) {
            return redirect()->back()->with('erreur', 'Montant hors limites du barème.');
        }

        $soldeActuel = $this->operationModel->calculateSolde($id_client, $telephone);
        if ($soldeActuel < ($montant + $frais)) {
            return redirect()->back()->with('erreur', 'Provision insuffisante pour finaliser le transfert.');
        }

        $this->operationModel->save([
            'id_client'          => $id_client,
            'id_type_operation'  => 3,
            'numero_destinataire'=> $destinataire,
            'montant'            => $montant,
            'frais_applique'     => $frais
        ]);

        return redirect()->to('/client/dashboard')->with('succes', 'Transfert envoyé avec succès !');
    }

    public function historique()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $id_client = $this->session->get('client_id');
        $telephone = $this->session->get('telephone');

        $data = [
            'title'      => 'Historique de mes transactions',
            'telephone'  => $telephone,
            'historique' => $this->operationModel->getHistory($id_client, $telephone)
        ];

        return view('client/historique', $data);
    }
}