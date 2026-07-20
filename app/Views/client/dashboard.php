<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    
    <!-- En-tête de bienvenue -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="fw-bold text-dark mb-1">Bonjour !</h2>
            <p class="text-muted">Numéro de compte : <span class="fw-semibold text-primary"><?= esc($telephone) ?></span></p>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-bold">
                <i class="bi bi-circle-fill me-1 small"></i> Compte Actif
            </span>
        </div>
    </div>

    <!-- Carte du Solde (Mise en valeur) -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow rounded-4 p-3 position-relative overflow-hidden">
                <!-- Décoration en arrière-plan -->
                <div class="position-absolute end-0 bottom-0 opacity-10 me-3 mb-2">
                    <i class="bi bi-wallet2" style="font-size: 8rem;"></i>
                </div>
                <div class="card-body">
                    <p class="text-white-50 text-uppercase tracking-wider small mb-1 fw-bold">Solde Disponible</p>
                    <h1 class="display-5 fw-bold mb-0">
                        <?= number_format($solde, 2, ',', ' ') ?> <span class="fs-3">Ar</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille des Actions Rapides -->
    <h5 class="fw-bold text-secondary mb-3">Opérations disponibles</h5>
    <div class="row g-3 mb-4">
        
        <!-- Bouton Dépôt -->
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/depot') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none hover-card">
                <div class="card-body">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-arrow-down-left-circle fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Dépôt</h6>
                </div>
            </a>
        </div>

        <!-- Bouton Retrait -->
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/retrait') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none hover-card">
                <div class="card-body">
                    <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-arrow-up-right-circle fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Retrait</h6>
                </div>
            </a>
        </div>

        <!-- Bouton Transfert -->
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/transfert') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none hover-card">
                <div class="card-body">
                    <div class="bg-info-subtle text-info rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-send fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Transfert</h6>
                </div>
            </a>
        </div>

        <!-- Bouton Historique -->
        <div class="col-6 col-md-3">
            <a href="<?= base_url('client/historique') ?>" class="card text-center border-0 shadow-sm h-100 py-3 rounded-4 text-decoration-none hover-card">
                <div class="card-body">
                    <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Historique</h6>
                </div>
            </a>
        </div>

    </div>

</div>

<!-- Style CSS additionnel pour l'effet de survol des boutons -->
<style>
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
<?= $this->endSection() ?>