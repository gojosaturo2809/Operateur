<aside class="sidebar">

    <a href="<?= base_url('operateur/dashboard') ?>" class="sidebar-brand">
        <i class="bi bi-phone-fill"></i>
        MobiMoney
        <span class="brand-badge">Admin</span>
    </a>

    <nav class="nav flex-column">

        <div class="sidebar-section-label">Navigation</div>

        <a href="<?= base_url('operateur/dashboard') ?>"
           class="nav-link <?= (uri_string() === 'operateur/dashboard' || uri_string() === 'operateur') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Tableau de bord
        </a>

        <a href="<?= base_url('operateur/clients') ?>"
           class="nav-link <?= (uri_string() === 'operateur/clients') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Comptes clients
        </a>

        <div class="sidebar-section-label">Configuration</div>

        <a href="<?= base_url('operateur/prefixes') ?>"
           class="nav-link <?= (uri_string() === 'operateur/prefixes') ? 'active' : '' ?>">
            <i class="bi bi-sim"></i> Préfixes
        </a>

        <a href="<?= base_url('operateur/types-operation') ?>"
           class="nav-link <?= (uri_string() === 'operateur/types-operation') ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right"></i> Types d'opération
        </a>

        <a href="<?= base_url('operateur/baremes') ?>"
           class="nav-link <?= (uri_string() === 'operateur/baremes') ? 'active' : '' ?>">
            <i class="bi bi-sliders"></i> Barèmes de frais
        </a>

        <div class="sidebar-section-label">Rapports</div>

        <a href="<?= base_url('operateur/gains') ?>"
           class="nav-link <?= (uri_string() === 'operateur/gains') ? 'active' : '' ?>">
            <i class="bi bi-graph-up-arrow"></i> Situation des gains
        </a>

        <a href="<?= base_url('operateur/compensation') ?>"
           class="nav-link <?= (uri_string() === 'operateur/compensation') ? 'active' : '' ?>">
            <i class="bi bi-arrow-down-up"></i> Compensation / Clearing
        </a>

        <hr>

        <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>

    </nav>
</aside>
