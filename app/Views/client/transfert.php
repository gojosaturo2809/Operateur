<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm" style="max-width:480px;">
    <div class="card-body p-4">
        <?php if (session()->getFlashdata('erreur')): ?>
            <div class="alert alert-danger py-2 small"><?= session()->getFlashdata('erreur') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('client/transfert') ?>">
            <div class="mb-3">
                <label class="form-label">Numéro destinataire</label>
                <input type="text" name="numero_destinataire" class="form-control form-control-lg" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Montant à transférer (Ar)</label>
                <input type="number" step="0.01" name="montant" class="form-control form-control-lg" required>
            </div>
            <div class="alert alert-light small border">Solde actuel : <?= number_format($solde ?? 0,0,',',' ') ?> Ar. Des frais s'appliquent selon le barème.</div>
            <button class="btn btn-primary btn-lg w-100">Confirmer le transfert</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
