<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ── En-tête ────────────────────────────────────────────────── -->
<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-2">
    <div>
        <h1>Compensation / Clearing</h1>
        <p>
            Commissions dues aux réseaux tiers sur les transferts sortants
            <span style="color:#94a3b8;">(montant × taux inter-opérateur)</span>.
        </p>
    </div>
    <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .85rem;
                 background:#fffbeb;color:#d97706;border:1px solid #fde68a;
                 border-radius:2rem;font-size:.78rem;font-weight:600;">
        <i class="bi bi-hourglass-split"></i> En attente de règlement — <?= date('d/m/Y') ?>
    </span>
</div>

<!-- ── KPI ────────────────────────────────────────────────────── -->
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-send-arrow-up"></i></div>
            <div>
                <div class="stat-label">Commissions dues</div>
                <div class="stat-value">
                    <?= number_format($totalCommissions ?? 0, 0, ',', ' ') ?>
                    <small class="fs-6 fw-normal text-muted">Ar</small>
                </div>
                <div class="stat-sub">Montant × taux inter</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-arrow-right-circle"></i></div>
            <div>
                <div class="stat-label">Volume transféré</div>
                <div class="stat-value">
                    <?= number_format($totalMontantTransfere ?? 0, 0, ',', ' ') ?>
                    <small class="fs-6 fw-normal text-muted">Ar</small>
                </div>
                <div class="stat-sub">Montants envoyés vers tiers</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-piggy-bank"></i></div>
            <div>
                <div class="stat-label">Frais conservés</div>
                <div class="stat-value">
                    <?= number_format($totalFraisPercus ?? 0, 0, ',', ' ') ?>
                    <small class="fs-6 fw-normal text-muted">Ar</small>
                </div>
                <div class="stat-sub">Marge opérateur</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-broadcast"></i></div>
            <div>
                <div class="stat-label">Réseaux tiers</div>
                <div class="stat-value"><?= (int) ($nbOperateurs ?? 0) ?></div>
                <div class="stat-sub">
                    <?= number_format($totalTransferts ?? 0, 0, ',', ' ') ?> transferts
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════
     Tableau principal de compensation
     Source : vue_compensation_operateurs
══════════════════════════════════════════════════════════════ -->
<div class="card mb-4">

    <div class="card-header d-flex align-items-center gap-3">
        <span style="width:2.25rem;height:2.25rem;border-radius:.5rem;
                     background:#fffbeb;color:#f59e0b;display:inline-flex;
                     align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
            <i class="bi bi-table"></i>
        </span>
        <div>
            <div style="font-weight:700;color:#0f172a;">Balances par réseau destinataire</div>
            <div style="font-size:.78rem;color:#64748b;">
                Commission = Montant transféré × Taux inter-opérateur
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Opérateur tiers</th>
                    <th class="text-center">Taux</th>
                    <th class="text-center">Transferts</th>
                    <th class="text-end">Montant transféré</th>
                    <th class="text-end">Frais conservés</th>
                    <th class="text-end">Période</th>
                    <th class="text-end" style="color:#ef4444;">Commission due</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($lignes)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4;"></i>
                        Aucun transfert inter-opérateur enregistré.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($lignes as $l):
                    $total = $totalCommissions ?? 0;
                    $pct   = ($total > 0 && ($l['commission_a_reverser'] ?? 0) > 0)
                             ? round($l['commission_a_reverser'] / $total * 100, 1)
                             : 0;
                ?>
                <tr>

                    <!-- Opérateur + barre de proportion -->
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            <span style="width:2rem;height:2rem;border-radius:.45rem;
                                         background:#f5f3ff;color:#7c3aed;display:flex;
                                         align-items:center;justify-content:center;
                                         font-size:.85rem;font-weight:800;flex-shrink:0;">
                                <?= mb_strtoupper(mb_substr($l['operateur_nom'] ?? '?', 0, 1)) ?>
                            </span>
                            <div>
                                <div style="font-weight:600;font-size:.9rem;color:#0f172a;">
                                    <?= esc($l['operateur_nom'] ?? '—') ?>
                                </div>
                                <!-- Barre proportion commission -->
                                <div style="width:120px;height:3px;background:#f1f5f9;
                                            border-radius:2px;margin-top:.3rem;overflow:hidden;">
                                    <div style="width:<?= $pct; ?>%;height:100%;
                                                background:#7c3aed;border-radius:2px;"></div>
                                </div>
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:.15rem;">
                                    <?= $pct ?> % du total
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Taux inter -->
                    <td class="text-center">
                        <?php if (($l['commission_inter_pct'] ?? 0) > 0): ?>
                        <span style="background:#fef3c7;color:#d97706;padding:.2rem .6rem;
                                     border-radius:2rem;font-size:.75rem;font-weight:700;
                                     white-space:nowrap;">
                            <?= number_format($l['commission_inter_pct'], 2) ?> %
                        </span>
                        <?php else: ?>
                        <span style="color:#94a3b8;font-size:.8rem;">0 %</span>
                        <?php endif; ?>
                    </td>

                    <!-- Nb transferts -->
                    <td class="text-center fw-semibold">
                        <?= number_format($l['nb_transferts'] ?? 0, 0, ',', ' ') ?>
                    </td>

                    <!-- Montant transféré -->
                    <td class="text-end" style="color:#374151;font-size:.9rem;">
                        <?= number_format($l['montant_transfere'] ?? 0, 0, ',', ' ') ?> Ar
                    </td>

                    <!-- Frais conservés -->
                    <td class="text-end" style="color:#10b981;font-weight:600;font-size:.9rem;">
                        <?= number_format($l['frais_percus'] ?? 0, 0, ',', ' ') ?> Ar
                    </td>

                    <!-- Période -->
                    <td class="text-end" style="font-size:.78rem;color:#94a3b8;white-space:nowrap;">
                        <?php if (!empty($l['premiere_operation'])): ?>
                            <?= date('d/m/Y', strtotime($l['premiere_operation'])) ?>
                            →
                            <?= date('d/m/Y', strtotime($l['derniere_operation'])) ?>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>

                    <!-- Commission due -->
                    <td class="text-end">
                        <span style="font-size:1rem;font-weight:700;color:#ef4444;">
                            <?= number_format($l['commission_a_reverser'] ?? 0, 0, ',', ' ') ?> Ar
                        </span>
                    </td>

                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>

            <?php if (!empty($lignes)): ?>
            <tfoot style="background:#fef2f2;border-top:2px solid #fecaca;">
                <tr>
                    <td colspan="3" style="font-weight:700;color:#0f172a;padding:.875rem 1rem;">
                        Totaux
                    </td>
                    <td class="text-end" style="color:#374151;font-weight:600;padding:.875rem 1rem;">
                        <?= number_format($totalMontantTransfere ?? 0, 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-end" style="color:#10b981;font-weight:700;padding:.875rem 1rem;">
                        <?= number_format($totalFraisPercus ?? 0, 0, ',', ' ') ?> Ar
                    </td>
                    <td style="padding:.875rem 1rem;"></td>
                    <td class="text-end" style="padding:.875rem 1rem;">
                        <span style="font-size:1.1rem;font-weight:800;color:#ef4444;">
                            <?= number_format($totalCommissions ?? 0, 0, ',', ' ') ?> Ar
                        </span>
                    </td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
        </div>
    </div>
</div>

<!-- ── Note de processus ──────────────────────────────────────── -->
<div style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;
            border-radius:.75rem;padding:1rem 1.25rem;
            display:flex;gap:.875rem;align-items:flex-start;">
    <i class="bi bi-info-circle-fill"
       style="color:#f59e0b;font-size:1.1rem;flex-shrink:0;margin-top:.1rem;"></i>
    <div style="font-size:.84rem;color:#78350f;line-height:1.65;">
        <strong>Calcul de la compensation :</strong>
        Pour chaque transfert sortant vers un réseau tiers,
        la <strong>commission due</strong> = <em>montant transféré × taux inter-opérateur</em>.
        Le client est débité du <strong>montant + frais</strong> ;
        nous conservons les <strong>frais</strong> et devons reverser la <strong>commission calculée</strong>
        à chaque partenaire selon les accords bilatéraux de clearing.
    </div>
</div>

<?= $this->endSection() ?>
