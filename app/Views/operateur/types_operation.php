<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="post" action="<?= base_url('operateur/types-operation/add') ?>" class="row g-2">
            <div class="col-auto">
                <input type="text" name="nom" class="form-control" placeholder="depot / retrait / transfert" required>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark"><i class="bi bi-plus-lg"></i> Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Nom</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach (($types ?? []) as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><span class="badge text-bg-secondary"><?= esc($t['nom']) ?></span></td>
                <td class="text-end">
                    <a href="<?= base_url('operateur/baremes?type=' . $t['id']) ?>" class="btn btn-sm btn-outline-dark">Barèmes</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
