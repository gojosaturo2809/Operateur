<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Types d'opération</h1>
    <p>Configurez les types d'opérations disponibles (dépôt, retrait, transfert…).</p>
</div>

<!-- ── Formulaire d'ajout ─────────────────────────────────────── -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle" style="color:var(--blue)"></i>
        Ajouter un type d'opération
    </div>
    <div class="card-body" style="padding:1.25rem 1.5rem;">
        <form method="post" action="<?= base_url('operateur/types-operation/store') ?>"
              class="d-flex align-items-end gap-3 flex-wrap">
            <?= csrf_field() ?>
            <div style="flex:1;min-width:220px;">
                <label class="form-label" style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem;">
                    Nom du type
                </label>
                <div style="position:relative;">
                    <i class="bi bi-arrow-left-right"
                       style="position:absolute;left:.875rem;top:50%;transform:translateY(-50%);
                              color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                    <input type="text"
                           name="nom"
                           class="form-control"
                           style="padding-left:2.5rem;height:2.75rem;"
                           placeholder="Ex : depot, retrait, transfert"
                           required>
                </div>
                <p style="font-size:.73rem;color:#94a3b8;margin:.35rem 0 0;">
                    Le nom sera converti en minuscules automatiquement.
                </p>
            </div>
            <button class="btn btn-primary d-flex align-items-center gap-2"
                    style="height:2.75rem;white-space:nowrap;">
                <i class="bi bi-plus-lg"></i> Ajouter
            </button>
        </form>
    </div>
</div>

<!-- ── Table des types ────────────────────────────────────────── -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-list-ul me-2"></i>Types enregistrés</span>
        <span class="badge" style="background:#eff6ff;color:#3b82f6;font-size:.75rem;">
            <?= count($types ?? []) ?> type<?= count($types ?? []) > 1 ? 's' : '' ?>
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($types)): ?>
                <tr>
                    <td colspan="3" class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-arrow-left-right d-block mb-2" style="font-size:1.75rem;opacity:.4;"></i>
                        Aucun type d'opération configuré.
                    </td>
                </tr>
            <?php else: ?>
                <?php
                $badgeMap = [
                    'depot'     => ['bg' => '#eff6ff', 'color' => '#3b82f6'],
                    'retrait'   => ['bg' => '#fef3c7', 'color' => '#d97706'],
                    'transfert' => ['bg' => '#f5f3ff', 'color' => '#7c3aed'],
                ];
                ?>
                <?php foreach ($types as $t):
                    $badge = $badgeMap[$t['nom']] ?? ['bg' => '#f1f5f9', 'color' => '#64748b'];
                ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:.85rem;"><?= esc($t['id']) ?></td>
                    <td>
                        <span class="badge-type"
                              style="background:<?= $badge['bg'] ?>;color:<?= $badge['color'] ?>;">
                            <?= esc($t['nom']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?= base_url('operateur/types-operation/delete/' . $t['id']) ?>"
                           onclick="return confirm('Supprimer le type «\u00a0<?= esc($t['nom']) ?>\u00a0» ?')"
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
