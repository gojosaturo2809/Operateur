<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ═══════════════════════════════════════════════════════════
     Page header
═══════════════════════════════════════════════════════════ -->
<div class="page-header">
    <h1>Tableau de bord</h1>
    <p>Vue d'ensemble de l'activité MobiMoney — <?= date('d/m/Y') ?></p>
</div>

<!-- ═══════════════════════════════════════════════════════════
     KPI Cards
═══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Total clients -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label">Clients</div>
                <div class="stat-value"><?= number_format($totalClients, 0, ',', ' ') ?></div>
                <div class="stat-sub">Comptes enregistrés</div>
            </div>
        </div>
    </div>

    <!-- Total opérations -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-label">Opérations</div>
                <div class="stat-value"><?= number_format($totalOperations, 0, ',', ' ') ?></div>
                <div class="stat-sub">Transactions totales</div>
            </div>
        </div>
    </div>

    <!-- Gains totaux -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="stat-label">Gains</div>
                <div class="stat-value"><?= number_format($totalGains, 0, ',', ' ') ?> <small class="fs-6 fw-normal text-muted">Ar</small></div>
                <div class="stat-sub">Frais retraits + transferts</div>
            </div>
        </div>
    </div>

    <!-- Volume financier -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-label">Volume financier</div>
                <div class="stat-value"><?= number_format($volumeFinancier, 0, ',', ' ') ?> <small class="fs-6 fw-normal text-muted">Ar</small></div>
                <div class="stat-sub">Somme des montants</div>
            </div>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════════════════════════
     Graphiques — ligne 1 : Barres + Donut
═══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Graphique en barres : gains & volumes par type -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Gains &amp; volumes par type</span>
                <span class="badge" style="background:#eff6ff;color:#3b82f6;font-size:.72rem;">Retraits &amp; Transferts</span>
            </div>
            <div class="card-body">
                <div class="chart-wrap" style="height:260px;">
                    <canvas id="chartBar"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique donut : répartition des transactions -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2 text-purple" style="color:#7c3aed"></i>Répartition des transactions
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="chart-wrap" style="height:260px;max-width:280px;">
                    <canvas id="chartDonut"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════════════════════════
     Graphiques — ligne 2 : Évolution mensuelle (pleine largeur)
═══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up me-2" style="color:#10b981"></i>Évolution mensuelle — opérations &amp; gains
            </div>
            <div class="card-body">
                <div class="chart-wrap" style="height:240px;">
                    <canvas id="chartLine"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     Dernières opérations
═══════════════════════════════════════════════════════════ -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clock-history me-2"></i>Dernières opérations</span>
        <a href="<?= base_url('operateur/gains') ?>" class="btn btn-sm btn-outline-secondary">Voir les gains</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Destinataire</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($dernieres)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Aucune opération enregistrée
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dernieres as $op): ?>
                    <tr>
                        <td class="text-muted"><?= esc($op['id']) ?></td>
                        <td><?= esc($op['numero_telephone']) ?></td>
                        <td>
                            <span class="badge-type badge-<?= esc($op['type_operation']) ?>">
                                <?= esc($op['type_operation']) ?>
                            </span>
                        </td>
                        <td class="text-muted"><?= $op['numero_destinataire'] ? esc($op['numero_destinataire']) : '—' ?></td>
                        <td class="text-end fw-semibold"><?= number_format($op['montant'], 0, ',', ' ') ?> Ar</td>
                        <td class="text-end" style="color:#10b981"><?= number_format($op['frais_applique'], 0, ',', ' ') ?> Ar</td>
                        <td class="text-muted" style="white-space:nowrap">
                            <?= date('d/m/Y H:i', strtotime($op['date_operation'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<!-- ═══════════════════════════════════════════════════════════
     Scripts — Chart.js
═══════════════════════════════════════════════════════════ -->
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
/* ── Données injectées depuis PHP ──────────────────────── */
const barLabels   = <?= $barLabels  ?>;
const barGains    = <?= $barGains   ?>;
const barVolumes  = <?= $barVolumes ?>;

const donutLabels = <?= $donutLabels ?>;
const donutData   = <?= $donutData  ?>;

const lineLabels  = <?= $lineLabels ?>;
const lineNb      = <?= $lineNb     ?>;
const lineGains   = <?= $lineGains  ?>;

/* ── Options globales Chart.js ─────────────────────────── */
Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.color       = '#64748b';

const COLORS = {
    blue:   '#3b82f6',
    purple: '#7c3aed',
    green:  '#10b981',
    amber:  '#f59e0b',
    red:    '#ef4444',
};

/* ── Graphique 1 : Barres ─────────────────────────────── */
new Chart(document.getElementById('chartBar'), {
    type: 'bar',
    data: {
        labels: barLabels,
        datasets: [
            {
                label: 'Gains (Ar)',
                data: barGains,
                backgroundColor: COLORS.green,
                borderRadius: 6,
                borderSkipped: false,
            },
            {
                label: 'Volume financier (Ar)',
                data: barVolumes,
                backgroundColor: COLORS.blue,
                borderRadius: 6,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.dataset.label + ' : '
                        + Number(ctx.parsed.y).toLocaleString('fr-FR') + ' Ar'
                }
            }
        },
        scales: {
            x: { grid: { display: false } },
            y: {
                grid: { color: '#f1f5f9' },
                ticks: {
                    callback: v => Number(v).toLocaleString('fr-FR') + ' Ar'
                }
            }
        }
    }
});

/* ── Graphique 2 : Donut ──────────────────────────────── */
const donutColors = [COLORS.blue, COLORS.amber, COLORS.purple];

new Chart(document.getElementById('chartDonut'), {
    type: 'doughnut',
    data: {
        labels: donutLabels,
        datasets: [{
            data: donutData,
            backgroundColor: donutColors,
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, usePointStyle: true }
            },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.label + ' : ' + ctx.parsed + ' opérations'
                }
            }
        }
    }
});

/* ── Graphique 3 : Courbe ─────────────────────────────── */
new Chart(document.getElementById('chartLine'), {
    type: 'line',
    data: {
        labels: lineLabels,
        datasets: [
            {
                label: 'Nombre d\'opérations',
                data: lineNb,
                borderColor: COLORS.blue,
                backgroundColor: 'rgba(59,130,246,.08)',
                borderWidth: 2,
                pointBackgroundColor: COLORS.blue,
                pointRadius: 4,
                tension: .35,
                fill: true,
                yAxisID: 'yLeft',
            },
            {
                label: 'Gains (Ar)',
                data: lineGains,
                borderColor: COLORS.green,
                backgroundColor: 'rgba(16,185,129,.08)',
                borderWidth: 2,
                pointBackgroundColor: COLORS.green,
                pointRadius: 4,
                tension: .35,
                fill: true,
                yAxisID: 'yRight',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const v = ctx.parsed.y;
                        if (ctx.datasetIndex === 1)
                            return ' Gains : ' + Number(v).toLocaleString('fr-FR') + ' Ar';
                        return ' Opérations : ' + v;
                    }
                }
            }
        },
        scales: {
            x:      { grid: { display: false } },
            yLeft:  { position: 'left',  grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
            yRight: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { callback: v => Number(v).toLocaleString('fr-FR') + ' Ar' }
            }
        }
    }
});
</script>
<?= $this->endSection() ?>
