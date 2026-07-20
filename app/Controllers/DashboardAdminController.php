<?php

namespace App\Controllers;

use App\Models\DashboardAdminModel;

class DashboardAdminController extends BaseController
{
    private DashboardAdminModel $model;

    public function __construct()
    {
        $this->model = new DashboardAdminModel();
    }

    /**
     * Tableau de bord principal de l'administrateur / opérateur.
     * Charge toutes les métriques et prépare les données pour les graphiques.
     */
    public function index(): string
    {
        // ── KPIs ───────────────────────────────────────────────────────────
        $totalClients     = $this->model->getTotalClients();
        $totalOperations  = $this->model->getTotalOperations();
        $totalGains       = $this->model->getTotalGains();
        $volumeFinancier  = $this->model->getVolumeFinancierTotal();

        // ── Données graphiques ─────────────────────────────────────────────
        $gainsByType      = $this->model->getGainsByType();       // bar chart
        $opsParType       = $this->model->getOperationsParType();  // doughnut
        $opsParMois       = $this->model->getOperationsParMois();  // line chart

        // ── Sérialisation JSON pour Chart.js ───────────────────────────────

        // Graphique 1 : Barres — gains et volume financier par type (retrait/transfert)
        $barLabels   = array_column($gainsByType, 'type_operation');
        $barGains    = array_column($gainsByType, 'total_gains');
        $barVolumes  = array_column($gainsByType, 'volume_financier');

        // Graphique 2 : Donut — répartition des transactions par type
        $donutLabels = array_column($opsParType, 'nom');
        $donutData   = array_column($opsParType, 'nb');

        // Graphique 3 : Courbe — évolution mensuelle
        $lineLabels  = array_column($opsParMois, 'mois');
        $lineNb      = array_column($opsParMois, 'nb');
        $lineGains   = array_column($opsParMois, 'gains');

        // ── Dernières opérations ───────────────────────────────────────────
        $dernieres = $this->model->getDernieresOperations(8);

        return view('operateur/dashboard', [
            'title'          => 'Tableau de bord',
            'pageTitle'      => 'Tableau de bord',
            'sidebar'        => 'sidebar_operateur',

            // KPIs
            'totalClients'    => $totalClients,
            'totalOperations' => $totalOperations,
            'totalGains'      => $totalGains,
            'volumeFinancier' => $volumeFinancier,

            // JSON pour charts
            'barLabels'       => json_encode($barLabels,  JSON_UNESCAPED_UNICODE),
            'barGains'        => json_encode($barGains),
            'barVolumes'      => json_encode($barVolumes),

            'donutLabels'     => json_encode($donutLabels, JSON_UNESCAPED_UNICODE),
            'donutData'       => json_encode($donutData),

            'lineLabels'      => json_encode($lineLabels,  JSON_UNESCAPED_UNICODE),
            'lineNb'          => json_encode($lineNb),
            'lineGains'       => json_encode($lineGains),

            // Table
            'dernieres'       => $dernieres,
        ]);
    }
}
