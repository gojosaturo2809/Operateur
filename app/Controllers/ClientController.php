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
            ->orderBy('p.prefixe', 'ASC')
            ->get()->getResultArray();

        return view('client/transfert', [
            'title'              => 'Faire un Transfert',
            'baremes'            => json_encode($baremes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
            'baremesRetrait'     => json_encode($baremesRetrait, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
            'prefixesOperateurs' => json_encode($prefixesOperateurs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
        ]);
    }

    /**
     * Formulaire d'envoi d'un montant réparti entre plusieurs destinataires.
     * Seuls les préfixes appartenant au réseau principal y sont affichés.
     */
    public function envoiMultiple()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $db = db_connect();
        $prefixesPrincipaux = $db->table('prefixes p')
            ->select('p.prefixe')
            ->join('operateurs op', 'op.id = p.id_operateur')
            ->where('op.est_principal', 1)
            ->orderBy('p.prefixe', 'ASC')
            ->get()->getResultArray();

        $baremes = $db->table('bareme_frais')
            ->select('montant_min, montant_max, frais')
            ->where('id_type_operation', 3)
            ->orderBy('montant_min', 'ASC')
            ->get()->getResultArray();

        return view('client/envoi_multiple', [
            'title'               => 'Envoi multiple divisé',
            'prefixesPrincipaux'  => $prefixesPrincipaux,
            'baremes'             => json_encode($baremes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
        ]);
    }

    /**
     * Enregistre un transfert par destinataire, tous liés par le même batch.
     */
    public function storeEnvoiMultiple()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $montantBrut = trim((string) $this->request->getPost('montant_global'));
        $montantBrut = str_replace([' ', ','], ['', '.'], $montantBrut);
        if ($montantBrut === '' || !is_numeric($montantBrut) || (float) $montantBrut <= 0) {
            return redirect()->back()->withInput()->with('erreur', 'Le montant global doit être supérieur à zéro.');
        }
        $montantGlobal = (float) $montantBrut;

        $numerosBruts = trim((string) $this->request->getPost('numeros_bruts'));
        $elements = preg_split('/[\s,;]+/', $numerosBruts, -1, PREG_SPLIT_NO_EMPTY);
        $numeros = [];
        foreach ($elements as $element) {
            $numero = preg_replace('/\D+/', '', $element);
            if ($numero === '' || strlen($numero) < 3) {
                return redirect()->back()->withInput()->with('erreur', 'Un numéro saisi est invalide.');
            }
            $numeros[] = $numero;
        }

        if (count($numeros) < 2) {
            return redirect()->back()->withInput()->with('erreur', 'Saisissez au moins deux numéros destinataires.');
        }
        if (count($numeros) !== count(array_unique($numeros))) {
            return redirect()->back()->withInput()->with('erreur', 'La liste contient des numéros en double.');
        }

        $db = db_connect();
        $prefixesPrincipaux = $db->table('prefixes p')
            ->select('p.prefixe')
            ->join('operateurs op', 'op.id = p.id_operateur')
            ->where('op.est_principal', 1)
            ->get()->getResultArray();
        $prefixesAutorises = array_column($prefixesPrincipaux, 'prefixe');
        $telephone = (string) $this->session->get('telephone');

        foreach ($numeros as $numero) {
            if ($numero === $telephone) {
                return redirect()->back()->withInput()->with('erreur', 'Impossible de vous inclure parmi les destinataires.');
            }
            if (!in_array(substr($numero, 0, 3), $prefixesAutorises, true)) {
                return redirect()->back()->withInput()->with(
                    'erreur',
                    'Envoi refusé : tous les destinataires doivent appartenir au réseau principal.'
                );
            }
        }

        $montantParPersonne = $montantGlobal / count($numeros);
        $fraisUnitaire = $this->operationModel->getFraisApplicable(3, $montantParPersonne);
        if ($fraisUnitaire === null) {
            return redirect()->back()->withInput()->with('erreur', 'Le montant par destinataire ne correspond à aucun barème de transfert.');
        }

        $fraisTotal = $fraisUnitaire * count($numeros);
        $idClient = (int) $this->session->get('client_id');
        $soldeActuel = $this->operationModel->calculateSolde($idClient, $telephone);
        if ($soldeActuel < ($montantGlobal + $fraisTotal)) {
            return redirect()->back()->withInput()->with('erreur', 'Solde insuffisant pour couvrir le montant global et les frais.');
        }

        $batch = 'BATCH_EM_' . date('Ymd') . '_' . strtoupper(bin2hex(random_bytes(6)));
        $db->transStart();
        foreach ($numeros as $numero) {
            $this->operationModel->insert([
                'id_client'             => $idClient,
                'id_type_operation'     => 3,
                'numero_destinataire'   => $numero,
                'montant'               => $montantParPersonne,
                'frais_applique'        => $fraisUnitaire,
                'batch_envoi_multiple'  => $batch,
            ]);
        }
        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('erreur', 'Une erreur est survenue : aucun transfert n\'a été enregistré.');
        }

        return redirect()->to('/client/dashboard')->with(
            'success',
            'Envoi multiple effectué pour ' . count($numeros) . ' destinataires.'
        );
    }

    public function storeTransfert()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $id_client    = $this->session->get('client_id');
        $telephone    = (string) $this->session->get('telephone');
        $destinataire = preg_replace('/\D+/', '', (string) $this->request->getPost('numero_destinataire'));
        $montant      = (float)$this->request->getPost('montant');

        if ($montant <= 0 || $destinataire === '' || strlen($destinataire) < 3) {
            return redirect()->back()->with('erreur', 'Le numéro et le montant du transfert sont invalides.');
        }
        if ($destinataire === $telephone) {
            return redirect()->back()->with('erreur', 'Opération invalide : impossible de s\'envoyer un transfert.');
        }

        // Identification du réseau de destination : le client ne peut pas
        // appliquer les frais de retrait à un transfert inter-opérateurs.
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

        $estReseauPrincipal = (int) $reseauDestination['est_principal'] === 1;
        // Cette valeur est volontairement forcée à 0 pour tout réseau tiers,
        // même si un formulaire manipulé soumet la case cochée.
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') === '1'
            && $estReseauPrincipal;
        $fraisRetrait = 0.0;
        if ($inclureFraisRetrait) {
            $fraisRetrait = $this->operationModel->getFraisApplicable(2, $montant);
            if ($fraisRetrait === null) {
                return redirect()->back()->with('erreur', 'Le montant ne correspond à aucun barème de retrait.');
            }
        }

        $commissionInter = $estReseauPrincipal
            ? 0.0
            : round($montant * ((float) $reseauDestination['commission_inter_pct'] / 100), 2);
        $fraisTotal = $frais + $commissionInter + $fraisRetrait;
        $soldeActuel = $this->operationModel->calculateSolde($id_client, $telephone);
        if ($soldeActuel < ($montant + $fraisTotal)) {
            return redirect()->back()->with('erreur', 'Provision insuffisante pour finaliser le transfert.');
        }

        $this->operationModel->save([
            'id_client'          => $id_client,
            'id_type_operation'  => 3,
            'numero_destinataire'=> $destinataire,
            'montant'            => $montant,
            'frais_applique'          => $fraisTotal,
            'inclure_frais_retrait'   => $inclureFraisRetrait ? 1 : 0,
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
