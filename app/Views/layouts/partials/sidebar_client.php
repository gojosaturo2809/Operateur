<aside class="sidebar bg-primary text-white">
    <div class="sidebar-brand d-flex align-items-center gap-2 p-3 border-bottom border-light border-opacity-25">
        <i class="bi bi-wallet2 fs-4"></i>
        <span class="fw-bold">MobiMoney</span>
    </div>
    <nav class="nav flex-column p-2">
        <a href="<?= base_url('client/dashboard') ?>" class="nav-link <?= (uri_string() == 'client/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-house-door me-2"></i> Solde &amp; Accueil
        </a>
        <a href="<?= base_url('client/depot') ?>" class="nav-link <?= (uri_string() == 'client/depot') ? 'active' : '' ?>">
            <i class="bi bi-plus-circle me-2"></i> Dépôt
        </a>
        <a href="<?= base_url('client/retrait') ?>" class="nav-link <?= (uri_string() == 'client/retrait') ? 'active' : '' ?>">
            <i class="bi bi-dash-circle me-2"></i> Retrait
        </a>
        <a href="<?= base_url('client/transfert') ?>" class="nav-link <?= (uri_string() == 'client/transfert') ? 'active' : '' ?>">
            <i class="bi bi-send me-2"></i> Transfert
        </a>
        <a href="<?= base_url('client/historique') ?>" class="nav-link <?= (uri_string() == 'client/historique') ? 'active' : '' ?>">
            <i class="bi bi-clock-history me-2"></i> Historique
        </a>
        <hr class="text-light text-opacity-25">
        <a href="<?= base_url('logout') ?>" class="nav-link text-warning">
            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
        </a>
    </nav>
</aside>
