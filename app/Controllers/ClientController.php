<?php

namespace App\Controllers;

use App\Models\OperationModel;

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
        $baremesRetrait = $db->query("SELECT montant_min, montant_max, frais FROM bareme_frais WHERE id_type_operation = 2")->getResultArray();
        $prefixesOperateurs = $db->table('prefixes p')
            ->select('p.prefixe, op.nom AS operateur_nom, op.est_principal, op.commission_inter_pct')
            ->join('operateurs op', 'op.id = p.id_operateur')
            ->get()->getResultArray();

        return view('client/transfert', [
            'title'   => 'Faire un Transfert',
            'baremes' => json_encode($baremes),
            'baremesRetrait' => json_encode($baremesRetrait),
            'prefixesOperateurs' => json_encode($prefixesOperateurs),
        ]);
    }

    public function envoiMultiple()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');
        $baremes = db_connect()->query("SELECT montant_min, montant_max, frais FROM bareme_frais WHERE id_type_operation = 3")->getResultArray();
        return view('client/envoi_multiple', ['title' => 'Envoi multiple', 'baremes' => json_encode($baremes)]);
    }

    public function storeEnvoiMultiple()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');
        $montant = (float) str_replace([' ', ','], ['', '.'], (string) $this->request->getPost('montant_global'));
        $numeros = array_values(array_filter(array_map(fn($n) => preg_replace('/\D+/', '', $n), preg_split('/[\s,;]+/', (string) $this->request->getPost('numeros_bruts')))));
        if ($montant <= 0 || count($numeros) < 2 || count($numeros) !== count(array_unique($numeros))) return redirect()->back()->withInput()->with('erreur', 'Saisissez un montant positif et au moins deux numéros différents.');
        $db = db_connect();
        foreach ($numeros as $numero) {
            if (strlen($numero) < 3 || !$db->table('prefixes')->where('prefixe', substr($numero, 0, 3))->get()->getRowArray()) return redirect()->back()->withInput()->with('erreur', 'Un numéro contient un préfixe inconnu.');
        }
        $part = $montant / count($numeros); $frais = $this->operationModel->getFraisApplicable(3, $part);
        if ($frais === null) return redirect()->back()->withInput()->with('erreur', 'Montant par personne hors barème.');
        $id = (int) $this->session->get('client_id'); $tel = (string) $this->session->get('telephone');
        if ($this->operationModel->calculateSolde($id, $tel) < $montant + $frais * count($numeros)) return redirect()->back()->withInput()->with('erreur', 'Solde insuffisant.');
        $batch = 'BATCH_EM_' . date('Ymd') . '_' . bin2hex(random_bytes(5)); $db->transStart();
        foreach ($numeros as $numero) $this->operationModel->insert(['id_client'=>$id,'id_type_operation'=>3,'numero_destinataire'=>$numero,'montant'=>$part,'frais_applique'=>$frais,'batch_envoi_multiple'=>$batch]);
        $db->transComplete();
        return $db->transStatus() ? redirect()->to('/client/dashboard')->with('success', 'Envoi multiple effectué.') : redirect()->back()->with('erreur', 'Erreur lors de l’envoi.');
    }

    public function storeTransfert()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $id_client    = $this->session->get('client_id');
        $telephone    = $this->session->get('telephone');
        $destinataire = preg_replace('/\D+/', '', (string)$this->request->getPost('numero_destinataire'));
        $montant      = (float)$this->request->getPost('montant');

        if ($montant <= 0 || strlen($destinataire) !== 10) {
            return redirect()->back()->with('erreur', 'Le numéro et le montant du transfert sont invalides.');
        }
        if ($destinataire === $telephone) {
            return redirect()->back()->with('erreur', 'Opération invalide : impossible de s\'envoyer un transfert.');
        }

        // Le transfert est autorisé vers tout opérateur enregistré via ses préfixes.
        $db = db_connect();
        $reseauDestination = $db->table('prefixes p')
            ->select('op.est_principal, op.commission_inter_pct')
            ->join('operateurs op', 'op.id = p.id_operateur')
            ->where('p.prefixe', substr($destinataire, 0, 3))
            ->get()->getRowArray();
        if ($reseauDestination === null) {
            return redirect()->back()->with('erreur', 'Numéro destinataire invalide (Préfixe non autorisé).');
        }

        $frais = $this->operationModel->getFraisApplicable(3, $montant); // 3 = Transfert
        if ($frais === null) {
            return redirect()->back()->with('erreur', 'Montant hors limites du barème.');
        }

        $commission = 0.0;
        if ((int) $reseauDestination['est_principal'] !== 1) {
            $commission = round($montant * (float) $reseauDestination['commission_inter_pct'] / 100, 2);
        }

        $inclure = $this->request->getPost('inclure_frais_retrait') === '1';
        $fraisRetrait = $inclure ? $this->operationModel->getFraisApplicable(2, $montant) : 0;
        if ($fraisRetrait === null) return redirect()->back()->with('erreur', 'Montant hors barème de retrait.');
        $fraisTotal = $frais + $commission + $fraisRetrait;
        $soldeActuel = $this->operationModel->calculateSolde($id_client, $telephone);
        if ($soldeActuel < ($montant + $fraisTotal)) {
            return redirect()->back()->with('erreur', 'Provision insuffisante pour finaliser le transfert.');
        }

        $this->operationModel->save([
            'id_client'          => $id_client,
            'id_type_operation'  => 3,
            'numero_destinataire'=> $destinataire,
            'montant'            => $montant,
            'frais_applique'     => $fraisTotal,
            'inclure_frais_retrait' => $inclure ? 1 : 0,
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Transfert envoyé avec succès !');
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
