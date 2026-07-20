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
        <h4 class="fw-bold mb-0"><i class="bi bi-phone me-2"></i>Préfixes Autorisés</h4>
        <!-- CORRIGÉ : operator -> operateur -->
        <a href="<?= base_url('operateur/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Menu Admin</a>
    </div>

    <!-- Messages Flash notifications -->
    <?php if(session()->getFlashdata('succes')): ?>
        <div class="alert alert-success py-2 small"><?= session()->getFlashdata('succes') ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout rapide -->
    <div class="card card-body shadow-sm border-0 mb-4">
        <!-- CORRIGÉ : operator -> operateur -->
        <form method="post" action="<?= base_url('operateur/prefixes/store') ?>" class="row g-2 align-items-center">
            <?= csrf_field() ?>
            <div class="col-8">
                <input type="text" name="prefixe" class="form-control" placeholder="Ex: 033 ou 037" maxlength="5" required>
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
                    <th>Préfixe Réseau</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($prefixes as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td class="fw-bold text-primary"><?= esc($p['prefixe']) ?></td>
                    <td class="text-end">
                        <!-- CORRIGÉ : operator -> operateur -->
                        <a href="<?= base_url('operateur/prefixes/delete/'.$p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce préfixe ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>