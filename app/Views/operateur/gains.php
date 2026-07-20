<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ── En-tête ────────────────────────────────────────────────── -->
<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-2">
    <div>
        <h1>Synthèse des gains</h1>
        <p>Répartition entre le réseau local et les commissions inter-opérateurs.</p>
    </div>
    <?php if (!empty($operateurPrincipal)): ?>
    <span style="display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .9rem;
                 background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;
                 border-radius:2rem;font-size:.8rem;font-weight:600;">
        <i class="bi bi-reception-4"></i>
        <?= esc($operateurPrincipal['nom']) ?>
        &nbsp;·&nbsp;Réseau principal
    </span>
    <?php endif; ?>
</div>

<!-- ── KPI Total général ─────────────────────────────────────── -->
<div class="row g-3 mb-4">

    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="stat-label">Gains totaux</div>
                <div class="stat-value"><?= number_format($totalGeneral ?? 0, 0, ',', ' ') ?> <small class="fs-6 fw-normal text-muted">Ar</small></div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-house-door"></i></div>
            <div>
                <div class="stat-label">Réseau local</div>
                <div class="stat-value"><?= number_format($totalLocal ?? 0, 0, ',', ' ') ?> <small class="fs-6 fw-normal text-muted">Ar</small></div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-label">Commissions inter</div>
                <div class="stat-value"><?= number_format($totalInter ?? 0, 0, ',', ' ') ?> <small class="fs-6 fw-normal text-muted">Ar</small></div>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════
     BLOC 1 — Gains Réseau Local
     Retraits + Transferts vers notre propre réseau
══════════════════════════════════════════════════════════════ -->
<div class="card mb-4">

    <div class="card-header d-flex align-items-center gap-3">
        <span style="width:2.25rem;height:2.25rem;border-radius:.5rem;
                     background:#ecfdf5;color:#10b981;display:inline-flex;
                     align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
            <i class="bi bi-house-fill"></i>
        </span>
        <div>
            <div style="font-weight:700;color:#0f172a;">Gains Réseau Local</div>
            <div style="font-size:.78rem;color:#64748b;">
                Retraits en agence + transferts vers nos préfixes
            </div>
        </div>
        <span style="margin-left:auto;font-size:.8rem;font-weight:700;
                     color:#10b981;background:#ecfdf5;padding:.3rem .8rem;
                     border-radius:2rem;border:1px solid #a7f3d0;">
            <?= number_format($totalLocal ?? 0, 0, ',', ' ') ?> Ar
        </span>
    </div>

    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Type d'opération</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-end">Volume financier</th>
                    <th class="text-end">Gains (frais)</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($gainsLocaux)): ?>
                <tr>
                    <td colspan="4" class="text-center py-4" style="color:var(--text-muted);">
                        <i class="bi bi-inbox d-block mb-1" style="opacity:.4;font-size:1.5rem;"></i>
                        Aucune opération locale.
                    </td>
                </tr>
            <?php else: ?>
                <?php
                $badgeMap = [
                    'retrait'   => ['bg'=>'#fef3c7','color'=>'#d97706'],
                    'transfert' => ['bg'=>'#eff6ff','color'=>'#3b82f6'],
                    'depot'     => ['bg'=>'#ecfdf5','color'=>'#10b981'],
                ];
                foreach ($gainsLocaux as $g):
                    $b = $badgeMap[$g['type_operation']] ?? ['bg'=>'#f1f5f9','color'=>'#64748b'];
                ?>
                <tr>
                    <td>
                        <span class="badge-type"
                              style="background:<?= $b['bg'] ?>;color:<?= $b['color'] ?>;">
                            <?= esc($g['type_operation']) ?>
                        </span>
                    </td>
                    <td class="text-center fw-semibold">
                        <?= number_format($g['volume_transactions'], 0, ',', ' ') ?>
                    </td>
                    <td class="text-end" style="color:var(--text-muted);">
                        <?= number_format($g['volume_financier'], 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-end fw-bold" style="color:#10b981;">
                        <?= number_format($g['total_gains'], 0, ',', ' ') ?> Ar
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot style="background:#f8fafc;">
                <tr>
                    <td colspan="3" class="text-end fw-semibold" style="color:var(--text-muted);font-size:.875rem;">
                        Total gains locaux
                    </td>
                    <td class="text-end fw-bold" style="color:#10b981;font-size:1rem;">
                        <?= number_format($totalLocal ?? 0, 0, ',', ' ') ?> Ar
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     BLOC 2 — Commissions Inter-Opérateurs
     Transferts vers des réseaux tiers enregistrés
══════════════════════════════════════════════════════════════ -->
<div class="card mb-4">

    <div class="card-header d-flex align-items-center gap-3">
        <span style="width:2.25rem;height:2.25rem;border-radius:.5rem;
                     background:#f5f3ff;color:#7c3aed;display:inline-flex;
                     align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
            <i class="bi bi-arrow-left-right"></i>
        </span>
        <div>
            <div style="font-weight:700;color:#0f172a;">Commissions Inter-Opérateurs</div>
            <div style="font-size:.78rem;color:#64748b;">
                Transferts vers les réseaux tiers enregistrés
            </div>
        </div>
        <span style="margin-left:auto;font-size:.8rem;font-weight:700;
                     color:#7c3aed;background:#f5f3ff;padding:.3rem .8rem;
                     border-radius:2rem;border:1px solid #ddd6fe;">
            <?= number_format($totalInter ?? 0, 0, ',', ' ') ?> Ar
        </span>
    </div>

    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Opérateur destinataire</th>
                    <th class="text-center">Taux commission</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-end">Volume financier</th>
                    <th class="text-end">Commissions perçues</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($gainsInter)): ?>
                <tr>
                    <td colspan="5" class="text-center py-4" style="color:var(--text-muted);">
                        <i class="bi bi-inbox d-block mb-1" style="opacity:.4;font-size:1.5rem;"></i>
                        Aucune commission inter-opérateur.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($gainsInter as $g): ?>
                <tr>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:.5rem;font-weight:600;">
                            <span style="width:1.6rem;height:1.6rem;border-radius:.35rem;
                                         background:#f5f3ff;color:#7c3aed;display:inline-flex;
                                         align-items:center;justify-content:center;font-size:.75rem;">
                                <i class="bi bi-broadcast"></i>
                            </span>
                            <?= esc($g['operateur_tiers']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?php if ($g['commission_inter_pct'] > 0): ?>
                        <span style="background:#fef3c7;color:#d97706;padding:.2rem .6rem;
                                     border-radius:2rem;font-size:.75rem;font-weight:700;">
                            <?= number_format($g['commission_inter_pct'], 2) ?> %
                        </span>
                        <?php else: ?>
                        <span style="color:#94a3b8;font-size:.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center fw-semibold">
                        <?= number_format($g['volume_transactions'], 0, ',', ' ') ?>
                    </td>
                    <td class="text-end" style="color:var(--text-muted);">
                        <?= number_format($g['volume_financier'], 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-end fw-bold" style="color:#7c3aed;">
                        <?= number_format($g['total_gains'], 0, ',', ' ') ?> Ar
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php
            // Transferts vers préfixes inconnus
            $inconnu = $gainsInterInconnus ?? [];
            $nbInconnu = (int)($inconnu['volume_transactions'] ?? 0);
            if ($nbInconnu > 0):
            ?>
                <tr style="background:#fffbeb;">
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:.5rem;
                                     font-size:.875rem;color:#92400e;">
                            <span style="width:1.6rem;height:1.6rem;border-radius:.35rem;
                                         background:#fef3c7;color:#d97706;display:inline-flex;
                                         align-items:center;justify-content:center;font-size:.75rem;">
                                <i class="bi bi-question-circle"></i>
                            </span>
                            Réseau inconnu (préfixe non référencé)
                        </span>
                    </td>
                    <td class="text-center"><span style="color:#94a3b8;font-size:.8rem;">—</span></td>
                    <td class="text-center fw-semibold"><?= number_format($nbInconnu, 0, ',', ' ') ?></td>
                    <td class="text-end" style="color:var(--text-muted);">
                        <?= number_format($inconnu['volume_financier'] ?? 0, 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-end fw-bold" style="color:#d97706;">
                        <?= number_format($inconnu['total_gains'] ?? 0, 0, ',', ' ') ?> Ar
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
            <tfoot style="background:#f8fafc;">
                <tr>
                    <td colspan="4" class="text-end fw-semibold" style="color:var(--text-muted);font-size:.875rem;">
                        Total commissions inter-opérateurs
                    </td>
                    <td class="text-end fw-bold" style="color:#7c3aed;font-size:1rem;">
                        <?= number_format($totalInter ?? 0, 0, ',', ' ') ?> Ar
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     Récapitulatif global
══════════════════════════════════════════════════════════════ -->
<div style="background:#0f172a;border-radius:.875rem;padding:1.25rem 1.5rem;
            display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
    <div>
        <div style="font-size:.75rem;font-weight:700;letter-spacing:.08em;
                    text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:.25rem;">
            Gain total opérateur
        </div>
        <div style="font-size:1.75rem;font-weight:700;color:#fff;line-height:1;">
            <?= number_format($totalGeneral ?? 0, 0, ',', ' ') ?>
            <span style="font-size:.9rem;font-weight:500;color:rgba(255,255,255,.5);">Ar</span>
        </div>
    </div>
    <div style="display:flex;gap:2rem;flex-wrap:wrap;">
        <div style="text-align:right;">
            <div style="font-size:.72rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.06em;">Local</div>
            <div style="font-size:1rem;font-weight:700;color:#10b981;">
                <?= number_format($totalLocal ?? 0, 0, ',', ' ') ?> Ar
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:.72rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.06em;">Inter</div>
            <div style="font-size:1rem;font-weight:700;color:#a78bfa;">
                <?= number_format($totalInter ?? 0, 0, ',', ' ') ?> Ar
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
