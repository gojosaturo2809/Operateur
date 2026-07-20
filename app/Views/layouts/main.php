<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'MobiMoney') ?> — MobiMoney</title>

<!-- Google Fonts: Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<!-- App CSS -->
<link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
</head>
<body>

<div class="app-shell">

    <!-- Sidebar -->
    <?= $this->include('layouts/partials/' . ($sidebar ?? 'sidebar_client')) ?>

    <!-- Main area -->
    <div class="content-wrapper">

        <!-- Topbar -->
        <header class="topbar">
            <h1 class="topbar-title"><?= esc($pageTitle ?? $title ?? '') ?></h1>
            <div class="topbar-user">
                <span><?= esc(session()->get('numero_telephone') ?? session()->get('operateur_nom') ?? '') ?></span>
                <div class="topbar-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

    </div><!-- /.content-wrapper -->

</div><!-- /.app-shell -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- App JS -->
<script src="<?= base_url('js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>