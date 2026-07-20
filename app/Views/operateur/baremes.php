<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="post" action="<?= base_url('operateur/baremes/add') ?>" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small mb-0">Type</label>
                <select name="id_type_operation" class="form-select">
                    <?php foreach (($types ?? []) as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= esc($t['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-0">Montant min</label>
                <input type="number" step="0.01" name="montant_min" class="form-control" required>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-0">Montant max</label>
                <input type="number" step="0.01" name="montant_max" class="form-control" required>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-0">Frais (Ar)</label>
                <input type="number" step="0.01" name="frais" class="form-control" required>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark"><i class="bi bi-plus-lg"></i> Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead><tr><th>Type</th><th>Tranche</th><th>Frais</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach (($baremes ?? []) as $b): ?>
            <tr>
                <td><?= esc($b['nom_type']) ?></td>
                <td><?= number_format($b['montant_min'],0,',',' ') ?> - <?= number_format($b['montant_max'],0,',',' ') ?> Ar</td>
                <td><?= number_format($b['frais'],0,',',' ') ?> Ar</td>
                <td class="text-end">
                    <a href="<?= base_url('operateur/baremes/delete/' . $b['id']) ?>" class="btn btn-sm btn-outline-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
