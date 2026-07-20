<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MobiMoney - Connexion</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center bg-primary min-vh-100">
<div class="card shadow-lg border-0" style="width: 380px;">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <i class="bi bi-wallet2 fs-1 text-primary"></i>
            <h4 class="fw-bold mt-2">MobiMoney</h4>
            <p class="text-muted small">Entrez votre numéro pour continuer</p>
        </div>
        <?php if (session()->getFlashdata('erreur')): ?>
            <div class="alert alert-danger py-2 small"><?= session()->getFlashdata('erreur') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('login') ?>">
            <div class="mb-3">
                <label class="form-label small">Numéro de téléphone</label>
                <input type="text" name="numero_telephone" class="form-control form-control-lg" placeholder="033 xx xxx xx" required>
            </div>
            <button class="btn btn-primary btn-lg w-100">Continuer</button>
        </form>
    </div>
</div>
</body>
</html>
