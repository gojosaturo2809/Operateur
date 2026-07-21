<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('client/dashboard') ?>" class="client-back">
    <i class="bi bi-arrow-left"></i> Retour
</a>

<div class="client-form-card">

    <div class="client-form-header">
        <span class="client-form-icon" style="background:#fef2f2;color:#ef4444;">
            <i class="bi bi-arrow-up-right-circle"></i>
        </span>
        <div>
            <p class="client-form-title">Pct epargne</p>
            <p class="client-form-sub">Pct epargne</p>
        </div>
    </div>

    <div class="client-form-body">

        <?php if (session()->getFlashdata('erreur')): ?>
            <div class="client-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= esc(session()->getFlashdata('erreur')) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('client/store-pct_epargne') ?>">
            <?= csrf_field() ?>

            <label class="client-field-label" for="pct_epargne">Pourcentage epargne</label>
            <div class="client-amount-wrap">
                <input
                    type="number"
                    id="pct_epargne"
                    name="pct_epargne"
                    class="client-amount-input"
                    placeholder="0"
                    min="0"
                    step="1"
                    inputmode="numeric"
                    required>

            </div>



            <input type="submit" id="btnSubmit" class="client-btn" style="background:#ef4444;" value="enregistrer">
            <i class="bi bi-check-circle-fill"></i>
           
        </form>

    </div>
</div>



<?= $this->endSection() ?>