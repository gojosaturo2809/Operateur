<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title><?= esc($title ?? 'MobiMoney') ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="client-body">

<!-- ══════════════════════════════════════════
     Header fixe
══════════════════════════════════════════ -->
<header class="client-header">
    <div class="client-header-brand">
        <i class="bi bi-wallet2"></i>
        <span>MobiMoney</span>
    </div>
    <div class="client-header-right">
        <span class="client-header-phone">
            <i class="bi bi-phone"></i>
            <?= esc(session()->get('telephone') ?? '') ?>
        </span>
        <a href="<?= base_url('logout') ?>" class="client-header-logout" title="Déconnexion">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</header>

<!-- ══════════════════════════════════════════
     Contenu principal
══════════════════════════════════════════ -->
<main class="client-main">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="client-alert client-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erreur')): ?>
        <div class="client-alert client-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= esc(session()->getFlashdata('erreur')) ?>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

</main>

<!-- ══════════════════════════════════════════
     Navigation bas (Bottom tab bar)
══════════════════════════════════════════ -->
<nav class="client-bottom-nav">

    <a href="<?= base_url('client/dashboard') ?>"
       class="client-nav-item <?= uri_string() === 'client/dashboard' ? 'active' : '' ?>">
        <i class="bi bi-house-door<?= uri_string() === 'client/dashboard' ? '-fill' : '' ?>"></i>
        <span>Accueil</span>
    </a>

    <a href="<?= base_url('client/depot') ?>"
       class="client-nav-item <?= uri_string() === 'client/depot' ? 'active' : '' ?>">
        <i class="bi bi-plus-circle<?= uri_string() === 'client/depot' ? '-fill' : '' ?>"></i>
        <span>Dépôt</span>
    </a>
    
      <a href="<?= base_url('client/pct_epargne') ?>"
       class="client-nav-item <?= uri_string() === 'client/depot' ? 'active' : '' ?>">
        <i class="bi bi-plus-circle<?= uri_string() === 'client/depot' ? '-fill' : '' ?>"></i>
        <span>Pct Epargne</span>
    </a>

    <a href="<?= base_url('client/retrait') ?>"
       class="client-nav-item <?= uri_string() === 'client/retrait' ? 'active' : '' ?>">
        <i class="bi bi-dash-circle<?= uri_string() === 'client/retrait' ? '-fill' : '' ?>"></i>
        <span>Retrait</span>
    </a>

    <a href="<?= base_url('client/transfert') ?>"
       class="client-nav-item <?= uri_string() === 'client/transfert' ? 'active' : '' ?>">
        <i class="bi bi-send<?= uri_string() === 'client/transfert' ? '-fill' : '' ?>"></i>
        <span>Transfert</span>
    </a>

    <a href="<?= base_url('client/historique') ?>"
       class="client-nav-item <?= uri_string() === 'client/historique' ? 'active' : '' ?>">
        <i class="bi bi-clock-history"></i>
        <span>Historique</span>
    </a>

</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
