<aside class="sidebar">

    <a href="<?= base_url('client/dashboard') ?>" class="sidebar-brand">
        <i class="bi bi-wallet2"></i>
        MobiMoney
        <span class="brand-badge" style="background:#10b981;">Client</span>
    </a>

    <nav class="nav flex-column">

        <div class="sidebar-section-label">Mon compte</div>

        <a href="<?= base_url('client/dashboard') ?>"
           class="nav-link <?= uri_string() === 'client/dashboard' ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i> Accueil
        </a>

        <a href="<?= base_url('client/historique') ?>"
           class="nav-link <?= uri_string() === 'client/historique' ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> Historique
        </a>

        <div class="sidebar-section-label">Opérations</div>

        <a href="<?= base_url('client/depot') ?>"
           class="nav-link <?= uri_string() === 'client/depot' ? 'active' : '' ?>">
            <i class="bi bi-plus-circle"></i> Dépôt
        </a>

        <a href="<?= base_url('client/retrait') ?>"
           class="nav-link <?= uri_string() === 'client/retrait' ? 'active' : '' ?>">
            <i class="bi bi-dash-circle"></i> Retrait
        </a>

        <a href="<?= base_url('client/transfert') ?>"
           class="nav-link <?= uri_string() === 'client/transfert' ? 'active' : '' ?>">
            <i class="bi bi-send"></i> Transfert
        </a>

        <hr>

        <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>

    </nav>
</aside>
