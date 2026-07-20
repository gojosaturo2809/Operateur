<header class="topbar d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom">
    <h5 class="mb-0"><?= $pageTitle ?? '' ?></h5>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small"><?= session()->get('numero_telephone') ?? session()->get('operateur_nom') ?? '' ?></span>
        <i class="bi bi-person-circle fs-4"></i>
    </div>
</header>
