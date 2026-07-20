<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MobiMoney - Mon Tableau de Bord</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar simplifiée -->
<nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">
            <i class="bi bi-wallet2 me-2"></i> MobiMoney
        </span>
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>
    </div>
</nav>

<div class="container py-2">
    <!-- Reprendre exactement le contenu de la section "content" ci-dessus -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="fw-bold text-dark mb-1">Bonjour !</h2>
            <p class="text-muted">Numéro de compte : <span class="fw-semibold text-primary"><?= esc($telephone) ?></span></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow rounded-4 p-3 position-relative overflow-hidden">
                <div class="card-body">
                    <p class="text-white-50 text-uppercase tracking-wider small mb-1 fw-bold">Solde Disponible</p>
                    <h1 class="display-5 fw-bold mb-0">
                        <?= number_format($solde, 2, ',', ' ') ?> <span class="fs-3">Ar</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold text-secondary mb-3">Opérations disponibles</h5>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/depot') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none">
                <div class="card-body">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-2"><i class="bi bi-arrow-down-left-circle fs-3"></i></div>
                    <h6 class="fw-bold text-dark mb-0">Dépôt</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/retrait') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none">
                <div class="card-body">
                    <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex p-3 mb-2"><i class="bi bi-arrow-up-right-circle fs-3"></i></div>
                    <h6 class="fw-bold text-dark mb-0">Retrait</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/transfert') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none">
                <div class="card-body">
                    <div class="bg-info-subtle text-info rounded-circle d-inline-flex p-3 mb-2"><i class="bi bi-send fs-3"></i></div>
                    <h6 class="fw-bold text-dark mb-0">Transfert</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/historique') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none">
                <div class="card-body">
                    <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex p-3 mb-2"><i class="bi bi-clock-history fs-3"></i></div>
                    <h6 class="fw-bold text-dark mb-0">Historique</h6>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>