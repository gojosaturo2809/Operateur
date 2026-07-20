<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-4">
<div class="container" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-gear me-2"></i>Configuration des opérations</h4>
        <a href="<?= base_url('operateur/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Menu Admin</a>
    </div>

    <!-- Messages Flash notifications -->
    <?php if(session()->getFlashdata('succes')): ?>
        <div class="alert alert-success py-2 small"><?= session()->getFlashdata('succes') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('erreur')): ?>
        <div class="alert alert-danger py-2 small"><?= session()->getFlashdata('erreur') ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout rapide -->
    <div class="card card-body shadow-sm border-0 mb-4">
        <form method="post" action="<?= base_url('operateur/types-operation/store') ?>" class="row g-2 align-items-center">
            <?= csrf_field() ?>
            <div class="col-8">
                <input type="text" name="nom" class="form-control" placeholder="Ex: depot, retrait, transfert" required>
            </div>
            <div class="col-4">
                <button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Ajouter</button>
            </div>
        </form>
    </div>

    <!-- Table des données -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom du Type</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($types as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td class="fw-bold text-capitalize"><?= esc($t['nom']) ?></td>
                    <td class="text-end">
                        <a href="<?= base_url('operateur/types-operation/delete/'.$t['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce type d\'opération ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($types)): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">Aucun type d'opération configuré.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>