<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('client/dashboard') ?>" class="client-back">
    <i class="bi bi-arrow-left"></i> Retour
</a>

<div class="client-form-card">

    <div class="client-form-header">
        <span class="client-form-icon" style="background:#ecfdf5;color:#10b981;">
            <i class="bi bi-arrow-down-left-circle"></i>
        </span>
        <div>
            <p class="client-form-title">Dépôt</p>
            <p class="client-form-sub">Alimenter votre compte en agence</p>
        </div>
    </div>

    <div class="client-form-body">

        <?php if (session()->getFlashdata('erreur')): ?>
        <div class="client-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= esc(session()->getFlashdata('erreur')) ?>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('client/store-depot') ?>">
            <?= csrf_field() ?>

            <label class="client-field-label" for="montant">Montant à déposer</label>
            <div class="client-amount-wrap">
                <input
                    type="number"
                    id="montant"
                    name="montant"
                    class="client-amount-input"
                    placeholder="0"
                    min="100"
                    step="100"
                    inputmode="numeric"
                    required
                >
                <span class="client-amount-unit">Ar</span>
            </div>

            <!-- Récap (affiché dès qu'on saisit) -->
            <div class="client-recap" id="recapBox" style="display:none;">
                <div class="client-recap-row">
                    <span>Montant versé</span>
                    <span class="fw-semibold" id="recapMontant">—</span>
                </div>
                <div class="client-recap-row client-recap-total">
                    <span>Crédit sur votre compte</span>
                    <span style="color:#10b981;" id="recapCredit">—</span>
                </div>
            </div>

            <p style="font-size:.75rem;color:#94a3b8;margin-bottom:1.25rem;">
                Montant minimum : 100 Ar
            </p>

            <button type="submit" class="client-btn" style="background:#10b981;">
                <i class="bi bi-check-circle-fill"></i>
                Confirmer le dépôt
            </button>
        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const input     = document.getElementById('montant');
const recapBox  = document.getElementById('recapBox');
const recapM    = document.getElementById('recapMontant');
const recapC    = document.getElementById('recapCredit');

function fmt(n) { return n.toLocaleString('fr-FR') + ' Ar'; }

input.addEventListener('input', function () {
    const v = parseFloat(this.value) || 0;
    if (v >= 100) {
        recapBox.style.display = '';
        recapM.textContent = fmt(v);
        recapC.textContent = fmt(v);
    } else {
        recapBox.style.display = 'none';
    }
});
</script>
<?= $this->endSection() ?>
