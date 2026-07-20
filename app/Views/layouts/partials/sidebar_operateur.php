<aside class="sidebar bg-dark text-white">
    <div class="sidebar-brand d-flex align-items-center gap-2 p-3 border-bottom border-secondary">
        <i class="bi bi-phone-fill fs-4"></i>
        <span class="fw-bold">MobiMoney <small class="text-warning">Opérateur</small></span>
    </div>
    <nav class="nav flex-column p-2">
        <a href="<?= base_url('operateur/dashboard') ?>" class="nav-link <?= (uri_string() == 'operateur/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
        </a>
        <a href="<?= base_url('operateur/prefixes') ?>" class="nav-link <?= (uri_string() == 'operateur/prefixes') ? 'active' : '' ?>">
            <i class="bi bi-sim me-2"></i> Préfixes
        </a>
        <a href="<?= base_url('operateur/types-operation') ?>" class="nav-link <?= (uri_string() == 'operateur/types-operation') ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right me-2"></i> Types d'opération
        </a>
        <a href="<?= base_url('operateur/baremes') ?>" class="nav-link <?= (uri_string() == 'operateur/baremes') ? 'active' : '' ?>">
            <i class="bi bi-cash-coin me-2"></i> Barèmes de frais
        </a>
        <a href="<?= base_url('operateur/gains') ?>" class="nav-link <?= (uri_string() == 'operateur/gains') ? 'active' : '' ?>">
            <i class="bi bi-graph-up-arrow me-2"></i> Situation des gains
        </a>
        <a href="<?= base_url('operateur/clients') ?>" class="nav-link <?= (uri_string() == 'operateur/clients') ? 'active' : '' ?>">
            <i class="bi bi-people me-2"></i> Comptes clients
        </a>
        <hr class="text-secondary">
        <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
        </a>
    </nav>
</aside>
