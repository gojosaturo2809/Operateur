<?php

namespace App\Controllers;

use App\Models\GainModel;
use App\Models\OperateurModel;
use App\Models\TypeOperationModel;
use App\Models\PrefixeModel;

class AdminOperateurController extends BaseController
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  SYNTHÈSE DES GAINS (page principale)
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Redirection depuis index() vers gainsSynthese()
     */
    public function index(): string
    {
        return $this->gainsSynthese();
    }

    /**
     * Synthèse complète : gains réseau local + commissions inter-opérateurs.
     */
    public function gainsSynthese(): string
    {
        $gainModel      = new GainModel();
        $operateurModel = new OperateurModel();

        // ── Bloc 1 : Gains réseau local ────────────────────────────────────
        $gainsLocaux  = $gainModel->getGainsLocal();
        $totalLocal   = $gainModel->getTotalGainsLocal();

        // ── Bloc 2 : Commissions inter-opérateurs ──────────────────────────
        $gainsInter      = $gainModel->getGainsInter();
        $gainsInterInco  = $gainModel->getGainsInterInconnus();
        $totalInter      = $gainModel->getTotalGainsInter();

        // ── Infos opérateur principal ──────────────────────────────────────
        $operateurPrincipal = $operateurModel->getOperateurPrincipal();

        return view('operateur/gains', [
            'title'              => 'Synthèse des gains',
            'pageTitle'          => 'Synthèse des gains',
            'sidebar'            => 'sidebar_operateur',

            // Bloc local
            'gainsLocaux'        => $gainsLocaux,
            'totalLocal'         => $totalLocal,

            // Bloc inter
            'gainsInter'         => $gainsInter,
            'gainsInterInconnus' => $gainsInterInco,
            'totalInter'         => $totalInter,

            // Grand total
            'totalGeneral'       => $totalLocal + $totalInter,

            // Opérateur principal
            'operateurPrincipal' => $operateurPrincipal,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  COMPENSATION / CLEARING
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Tableau de compensation / clearing inter-opérateurs.
     * Montre les montants nets à reverser à chaque réseau tiers.
     */
   public function compensation(): string
{
    $gainModel = new GainModel();

    return view('operateur/compensation', [
        'title'                   => 'Compensation / Clearing',
        'pageTitle'               => 'Compensation Inter-Opérateurs',
        'sidebar'                 => 'sidebar_operateur',

        'lignes'                  => $gainModel->getCompensationParOperateur(),
        'totalCommissions'        => $gainModel->getTotalCompensation(),
        'totalMontantTransfere'   => $gainModel->getTotalMontantTransfere(),
        'totalFraisPercus'        => $gainModel->getTotalFraisPercus(),
        'totalTransferts'         => $gainModel->getTotalTransferts(),
        'nbOperateurs'            => $gainModel->getNombreOperateurs(),
    ]);
}

    // ══════════════════════════════════════════════════════════════════════════
    //  CLIENTS
    // ══════════════════════════════════════════════════════════════════════════

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

    // ══════════════════════════════════════════════════════════════════════════
    //  PRÉFIXES
    // ══════════════════════════════════════════════════════════════════════════

    public function prefixes(): string
    {
        $prefixes = $this->db->query("
            SELECT p.id, p.prefixe, op.nom AS operateur_nom, op.est_principal
            FROM prefixes p
            JOIN operateurs op ON p.id_operateur = op.id
            ORDER BY op.est_principal DESC, p.prefixe ASC
        ")->getResultArray();

        $operateurs = $this->db->table('operateurs')
            ->orderBy('est_principal', 'DESC')
            ->get()->getResultArray();

        return view('operateur/prefixes', [
            'title'      => 'Préfixes autorisés',
            'pageTitle'  => 'Préfixes autorisés',
            'sidebar'    => 'sidebar_operateur',
            'prefixes'   => $prefixes,
            'operateurs' => $operateurs,
        ]);
    }

    public function storePrefixe(): \CodeIgniter\HTTP\RedirectResponse
    {
        $prefixe    = trim($this->request->getPost('prefixe') ?? '');
        $idOperateur = (int) $this->request->getPost('id_operateur');

        if (empty($prefixe) || $idOperateur === 0) {
            return redirect()->back()->with('error', 'Préfixe et opérateur sont obligatoires.');
        }

        try {
            $this->db->table('prefixes')->insert([
                'prefixe'      => $prefixe,
                'id_operateur' => $idOperateur,
            ]);
            return redirect()->back()->with('success', "Préfixe « $prefixe » ajouté.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ce préfixe existe déjà.');
        }
    }

    public function deletePrefixe(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->db->table('prefixes')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Préfixe supprimé.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  TYPES D'OPÉRATION
    // ══════════════════════════════════════════════════════════════════════════

    public function typesOperation(): string
    {
        $types = $this->db->table('types_operation')
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        return view('operateur/types_operation', [
            'title'     => "Types d'opération",
            'pageTitle' => "Types d'opération",
            'sidebar'   => 'sidebar_operateur',
            'types'     => $types,
        ]);
    }

    public function storeTypeOperation(): \CodeIgniter\HTTP\RedirectResponse
    {
        $nom = strtolower(trim($this->request->getPost('nom') ?? ''));

        if (empty($nom)) {
            return redirect()->back()->with('error', 'Le nom est obligatoire.');
        }

        $this->db->table('types_operation')->insert(['nom' => $nom]);
        return redirect()->back()->with('success', "Type « $nom » créé.");
    }

    public function deleteTypeOperation(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->db->table('types_operation')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Type supprimé.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  BARÈMES DE FRAIS
    // ══════════════════════════════════════════════════════════════════════════

    public function baremes(): string
    {
        $baremes = $this->db->query("
            SELECT b.id, t.nom AS type_nom,
                   b.montant_min, b.montant_max, b.frais
            FROM bareme_frais b
            JOIN types_operation t ON t.id = b.id_type_operation
            ORDER BY t.nom, b.montant_min
        ")->getResultArray();

        $types = $this->db->table('types_operation')->get()->getResultArray();

        return view('operateur/baremes', [
            'title'     => 'Barèmes des frais',
            'pageTitle' => 'Barèmes des frais par tranche',
            'sidebar'   => 'sidebar_operateur',
            'baremes'   => $baremes,
            'types'     => $types,
        ]);
    }

    public function storeBareme(): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->db->table('bareme_frais')->insert([
            'id_type_operation' => (int)   $this->request->getPost('id_type_operation'),
            'montant_min'       => (float) $this->request->getPost('montant_min'),
            'montant_max'       => (float) $this->request->getPost('montant_max'),
            'frais'             => (float) $this->request->getPost('frais'),
        ]);
        return redirect()->back()->with('success', 'Règle tarifaire ajoutée.');
    }

    public function deleteBareme(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->db->table('bareme_frais')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Règle tarifaire supprimée.');
    }
}
