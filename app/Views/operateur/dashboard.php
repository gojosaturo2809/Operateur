<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Gains retraits</div>
                <div class="fs-3 fw-bold"><?= number_format($gainsRetrait ?? 0, 0, ',', ' ') ?> Ar</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Gains transferts</div>
                <div class="fs-3 fw-bold"><?= number_format($gainsTransfert ?? 0, 0, ',', ' ') ?> Ar</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Comptes clients</div>
                <div class="fs-3 fw-bold"><?= $nbClients ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
