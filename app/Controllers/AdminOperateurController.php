<?php

namespace App\Controllers;

use App\Models\GainModel;
use App\Models\ClientModel;
use App\Models\AdminModel;
use App\Models\TypeAdminModel;
use App\Models\PrefixeModel;

class AdminOperateurController extends BaseController
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Vue : Situation des gains (Tableau de bord principal)
     */
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

    /**
     * Vue : Liste de la situation des comptes clients
     */
    public function clients(): string
    {
        $clients = $this->db->table('clients')
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        // Le solde n'est pas une colonne persistée : il dépend des opérations
        // (dépôts, retraits et transferts reçus/émis). On le calcule donc avec
        // la même règle que celle utilisée sur le tableau de bord du client.
        $AdminModel = new AdminModel();
        foreach ($clients as &$client) {
            $client['solde'] = $AdminModel->calculateSolde(
                (int) $client['id'],
                (string) $client['numero_telephone']
            );
        }
        unset($client);

        return view('operateur/clients', [
            'title'     => 'Comptes clients',
            'pageTitle' => 'Comptes clients',
            'sidebar'   => 'sidebar_operateur',
            'clients'   => $clients,
        ]);
    }

    // =============================================================================
    // 1. CRUD : PRÉFIXES AUTORISÉS
    // =============================================================================

    public function prefixes(): string
    {
        $prefixes = $this->db->table('prefixes')
            ->orderBy('prefixe', 'ASC')
            ->get()->getResultArray();

        return view('operateur/prefixes', [
            'title'     => 'Gestion des préfixes',
            'pageTitle' => 'Préfixes autorisés',
            'sidebar'   => 'sidebar_operateur',
            'prefixes'  => $prefixes,
        ]);
    }

    public function storePrefixe()
    {
        $prefixe = $this->request->getPost('prefixe');

        if (!empty($prefixe)) {
            $this->db->table('prefixes')->insert([
                'prefixe' => trim($prefixe)
            ]);
            return redirect()->back()->with('success', 'Préfixe ajouté avec succès !');
        }

        return redirect()->back()->with('error', 'Le champ préfixe ne peut pas être vide.');
    }

    public function deletePrefixe(int $id)
    {
        $this->db->table('prefixes')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Préfixe supprimé avec succès.');
    }

    // =============================================================================
    // 2. CRUD : TYPES D'OPÉRATIONS
    // =============================================================================

    public function typesOperation(): string
    {
        $types = $this->db->table('types_operation')
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        return view('operateur/types_operation', [
            'title'     => 'Types d\'opérations',
            'pageTitle' => 'Configuration des opérations',
            'sidebar'   => 'sidebar_operateur',
            'types'     => $types,
        ]);
    }

    public function storeTypeOperation()
    {
        $nom = $this->request->getPost('nom');

        if (!empty($nom)) {
            $this->db->table('types_operation')->insert([
                'nom' => strtolower(trim($nom))
            ]);
            return redirect()->back()->with('success', 'Type d\'opération créé avec succès.');
        }

        return redirect()->back()->with('error', 'Le nom du type d\'opération est obligatoire.');
    }

    public function deleteTypeOperation(int $id)
    {
        $this->db->table('types_operation')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Type d\'opération retiré avec succès.');
    }

    // =============================================================================
    // 3. CRUD : BARÈMES DE FRAIS
    // =============================================================================

    public function baremes(): string
    {
        // Récupération des barèmes combinés avec le libellé de leur type d'opération
        $baremes = $this->db->table('bareme_frais b')
            ->select('b.*, t.nom as type_nom')
            ->join('types_operation t', 't.id = b.id_type_operation')
            ->orderBy('b.id_type_operation', 'ASC')
            ->orderBy('b.montant_min', 'ASC')
            ->get()->getResultArray();

        // Récupération des types d'opérations pour alimenter le select du formulaire
        $types = $this->db->table('types_operation')->get()->getResultArray();

        return view('operateur/baremes', [
            'title'     => 'Barèmes des frais',
            'pageTitle' => 'Barèmes des frais par tranche',
            'sidebar'   => 'sidebar_operateur',
            'baremes'   => $baremes,
            'types'     => $types,
        ]);
    }

    public function storeBareme()
    {
        $this->db->table('bareme_frais')->insert([
            'id_type_operation' => (int) $this->request->getPost('id_type_operation'),
            'montant_min'       => (float) $this->request->getPost('montant_min'),
            'montant_max'       => (float) $this->request->getPost('montant_max'),
            'frais'             => (float) $this->request->getPost('frais'),
        ]);

        return redirect()->back()->with('success', 'Nouvelle règle tarifaire ajoutée au barème !');
    }

    public function deleteBareme(int $id)
    {
        $this->db->table('bareme_frais')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Règle tarifaire supprimée du barème.');
    }
}
