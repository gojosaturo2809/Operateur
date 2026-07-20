<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg,#0d6efd,#0a58ca);">
    <div class="card-body p-4">
        <div class="small opacity-75">Solde disponible</div>
        <div class="display-6 fw-bold"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</div>
        <div class="small opacity-75 mt-1"><?= esc($numero_telephone ?? '') ?></div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-4">
        <a href="<?= base_url('client/depot') ?>" class="btn btn-outline-primary w-100 py-3"><i class="bi bi-plus-circle fs-4 d-block mb-1"></i>Dépôt</a>
    </div>
    <div class="col-4">
        <a href="<?= base_url('client/retrait') ?>" class="btn btn-outline-primary w-100 py-3"><i class="bi bi-dash-circle fs-4 d-block mb-1"></i>Retrait</a>
    </div>
    <div class="col-4">
        <a href="<?= base_url('client/transfert') ?>" class="btn btn-outline-primary w-100 py-3"><i class="bi bi-send fs-4 d-block mb-1"></i>Transfert</a>
    </div>
</div>
<?= $this->endSection() ?>
