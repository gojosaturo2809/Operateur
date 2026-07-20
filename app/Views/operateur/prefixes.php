<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="post" action="<?= base_url('operateur/prefixes/add') ?>" class="row g-2">
            <div class="col-auto">
                <input type="text" name="prefixe" class="form-control" placeholder="Ex: 033" maxlength="3" required>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark"><i class="bi bi-plus-lg"></i> Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Préfixe</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach (($prefixes ?? []) as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= esc($p['prefixe']) ?></td>
                <td class="text-end">
                    <a href="<?= base_url('operateur/prefixes/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
