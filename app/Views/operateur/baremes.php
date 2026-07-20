<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Barèmes des frais</h1>
    <p>Définissez les frais appliqués par tranche de montant pour chaque type d'opération.</p>
</div>

<!-- ── Formulaire d'ajout ─────────────────────────────────────── -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle" style="color:var(--blue)"></i>
        Ajouter une règle tarifaire
    </div>
    <div class="card-body" style="padding:1.25rem 1.5rem;">
        <form method="post" action="<?= base_url('operateur/baremes/store') ?>">
            <?= csrf_field() ?>
            <div class="row g-3 align-items-end">

                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                        Type d'opération
                    </label>
                    <select name="id_type_operation" class="form-select" style="height:2.75rem;" required>
                        <option value="">Choisir…</option>
                        <?php foreach ($types ?? [] as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= esc(ucfirst($t['nom'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                        Montant min (Ar)
                    </label>
                    <input type="number" step="0.01" min="0"
                           name="montant_min"
                           class="form-control" style="height:2.75rem;"
                           placeholder="0" required>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                        Montant max (Ar)
                    </label>
                    <input type="number" step="0.01" min="0"
                           name="montant_max"
                           class="form-control" style="height:2.75rem;"
                           placeholder="100 000" required>
                </div>

                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                        Frais (Ar)
                    </label>
                    <input type="number" step="0.01" min="0"
                           name="frais"
                           class="form-control" style="height:2.75rem;"
                           placeholder="500" required>
                </div>

                <div class="col-lg-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1"
                            style="height:2.75rem;" title="Ajouter la règle">
                        <i class="bi bi-plus-lg"></i>
                        <span class="d-none d-xl-inline">Ajouter</span>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ── Table des barèmes ──────────────────────────────────────── -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-table me-2"></i>Règles tarifaires</span>
        <span class="badge" style="background:#eff6ff;color:#3b82f6;font-size:.75rem;">
            <?= count($baremes ?? []) ?> règle<?= count($baremes ?? []) > 1 ? 's' : '' ?>
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Tranche min</th>
                    <th>Tranche max</th>
                    <th class="text-end">Frais</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($baremes)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-cash-coin d-block mb-2" style="font-size:1.75rem;opacity:.4;"></i>
                        Aucun barème tarifaire enregistré.
                    </td>
                </tr>
            <?php else: ?>
                <?php
                $badgeMap = [
                    'depot'     => ['bg' => '#eff6ff', 'color' => '#3b82f6'],
                    'retrait'   => ['bg' => '#fef3c7', 'color' => '#d97706'],
                    'transfert' => ['bg' => '#f5f3ff', 'color' => '#7c3aed'],
                ];
                $lastType = null;
                ?>
                <?php foreach ($baremes as $b):
                    $badge = $badgeMap[$b['type_nom']] ?? ['bg' => '#f1f5f9', 'color' => '#64748b'];
                ?>
                <tr>
                    <td>
                        <span class="badge-type"
                              style="background:<?= $badge['bg'] ?>;color:<?= $badge['color'] ?>;">
                            <?= esc($b['type_nom']) ?>
                        </span>
                    </td>
                    <td style="font-size:.9rem;color:#374151;">
                        <?= number_format($b['montant_min'], 0, ',', ' ') ?> Ar
                    </td>
                    <td style="font-size:.9rem;color:#374151;">
                        <?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-end">
                        <span style="font-weight:700;color:#10b981;font-size:.95rem;">
                            <?= number_format($b['frais'], 0, ',', ' ') ?> Ar
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?= base_url('operateur/baremes/delete/' . $b['id']) ?>"
                           onclick="return confirm('Supprimer cette règle de frais ?')"
                           class="btn btn-sm"
                           style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;
                                  font-size:.8rem;padding:.35rem .75rem;border-radius:.45rem;">
                            <i class="bi bi-trash"></i>
                        </a>
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
