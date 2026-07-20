<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>MobiMoney - Dépôt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 500px;">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <a href="<?= base_url('client/dashboard') ?>" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Retour</a>
            <h4 class="fw-bold mt-3 mb-4 text-success"><i class="bi bi-arrow-down-left-circle me-2"></i>Alimenter mon compte</h4>
            
            <?php if (session()->getFlashdata('erreur')): ?>
                <div class="alert alert-danger py-2 small"><?= esc(session()->getFlashdata('erreur')) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('client/store-depot') ?>">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="form-label font-semibold small">Montant à déposer (Ar)</label>
                    <input type="number" name="montant" class="form-control form-control-lg text-center fw-bold" placeholder="Ex: 50000" min="100" required>
                </div>
                <button class="btn btn-success btn-lg w-100 rounded-3">Confirmer le Dépôt</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>