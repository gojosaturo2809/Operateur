<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Préfixes autorisés</h1>
    <p>Associez chaque préfixe réseau à son opérateur (local ou tiers).</p>
</div>

<!-- ── Formulaire d'ajout ─────────────────────────────────────── -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle" style="color:var(--blue)"></i>
        Ajouter un préfixe
    </div>
    <div class="card-body" style="padding:1.25rem 1.5rem;">
        <form method="post" action="<?= base_url('operateur/prefixes/store') ?>"
              class="d-flex align-items-end gap-3 flex-wrap">
            <?= csrf_field() ?>

            <div style="flex:1;min-width:160px;">
                <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                    Préfixe réseau
                </label>
                <div style="position:relative;">
                    <i class="bi bi-sim"
                       style="position:absolute;left:.875rem;top:50%;transform:translateY(-50%);
                              color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                    <input type="text"
                           name="prefixe"
                           class="form-control"
                           style="padding-left:2.5rem;height:2.75rem;"
                           placeholder="Ex : 033"
                           maxlength="5"
                           required>
                </div>
            </div>

            <div style="flex:1;min-width:200px;">
                <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                    Opérateur associé
                </label>
                <select name="id_operateur" class="form-select" style="height:2.75rem;" required>
                    <option value="">Choisir…</option>
                    <?php foreach ($operateurs ?? [] as $op): ?>
                    <option value="<?= $op['id'] ?>">
                        <?= esc($op['nom']) ?>
                        <?= $op['est_principal'] ? ' (principal)' : ' (tiers)' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-primary d-flex align-items-center gap-2"
                    style="height:2.75rem;white-space:nowrap;">
                <i class="bi bi-plus-lg"></i> Ajouter
            </button>
        </form>
    </div>
</div>

<!-- ── Table des préfixes ─────────────────────────────────────── -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-list-ul me-2"></i>Préfixes enregistrés</span>
        <span class="badge" style="background:#eff6ff;color:#3b82f6;font-size:.75rem;">
            <?= count($prefixes ?? []) ?> préfixe<?= count($prefixes ?? []) > 1 ? 's' : '' ?>
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Préfixe</th>
                    <th>Opérateur</th>
                    <th>Type</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($prefixes)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-sim d-block mb-2" style="font-size:1.75rem;opacity:.4;"></i>
                        Aucun préfixe enregistré.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($prefixes as $p): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:.85rem;"><?= esc($p['id']) ?></td>
                    <td>
                        <span style="font-size:.95rem;font-weight:700;font-family:monospace;
                                     color:#0f172a;letter-spacing:.05em;">
                            <?= esc($p['prefixe']) ?>
                        </span>
                    </td>
                    <td style="font-size:.875rem;color:#374151;">
                        <?= esc($p['operateur_nom'] ?? '—') ?>
                    </td>
                    <td>
                        <?php if ($p['est_principal']): ?>
                        <span style="background:#ecfdf5;color:#059669;padding:.2rem .65rem;
                                     border-radius:2rem;font-size:.73rem;font-weight:700;border:1px solid #a7f3d0;">
                            <i class="bi bi-house-fill me-1" style="font-size:.65rem;"></i>Local
                        </span>
                        <?php else: ?>
                        <span style="background:#f5f3ff;color:#7c3aed;padding:.2rem .65rem;
                                     border-radius:2rem;font-size:.73rem;font-weight:700;border:1px solid #ddd6fe;">
                            <i class="bi bi-arrow-left-right me-1" style="font-size:.65rem;"></i>Tiers
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= base_url('operateur/prefixes/delete/' . $p['id']) ?>"
                           onclick="return confirm('Supprimer le préfixe <?= esc($p['prefixe']) ?> ?')"
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

<?= $this->endSection() ?>
