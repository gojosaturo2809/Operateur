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
            <p class="client-form-title">Retrait</p>
            <p class="client-form-sub">Retrait en agence — frais selon barème</p>
        </div>
    </div>

    <div class="client-form-body">

        <?php if (session()->getFlashdata('erreur')): ?>
        <div class="client-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= esc(session()->getFlashdata('erreur')) ?>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('client/store-retrait') ?>">
            <?= csrf_field() ?>

            <label class="client-field-label" for="montantInput">Montant à retirer</label>
            <div class="client-amount-wrap">
                <input
                    type="number"
                    id="montantInput"
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

            <!-- Récapitulatif frais -->
            <div class="client-recap">
                <div class="client-recap-row">
                    <span>Montant retiré</span>
                    <span id="recapMontant" style="color:#64748b;">—</span>
                </div>
                <div class="client-recap-row">
                    <span>Frais appliqués</span>
                    <span id="txtFrais" style="color:#f59e0b;font-weight:600;">—</span>
                </div>
                <div class="client-recap-row client-recap-total">
                    <span>Total débité</span>
                    <span id="txtTotal" style="color:#ef4444;">—</span>
                </div>
            </div>

            <div id="warnBareme" class="client-warn" style="display:none;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Montant hors barème — opération indisponible.
            </div>

            <button type="submit" id="btnSubmit" class="client-btn" style="background:#ef4444;">
                <i class="bi bi-check-circle-fill"></i>
                Valider le retrait
            </button>
        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const baremes  = <?= $baremes ?? '[]' ?>;
const input    = document.getElementById('montantInput');
const recapM   = document.getElementById('recapMontant');
const txtFrais = document.getElementById('txtFrais');
const txtTotal = document.getElementById('txtTotal');
const warn     = document.getElementById('warnBareme');
const btn      = document.getElementById('btnSubmit');

function fmt(n) { return n.toLocaleString('fr-FR') + ' Ar'; }

input.addEventListener('input', function () {
    const montant = parseFloat(this.value) || 0;
    let frais = 0, trouve = false;

    if (montant > 0) {
        for (const b of baremes) {
            if (montant >= b.montant_min && montant <= b.montant_max) {
                frais = parseFloat(b.frais);
                trouve = true;
                break;
            }
        }
    }

    if (montant > 0 && trouve) {
        recapM.textContent  = fmt(montant);
        txtFrais.textContent = fmt(frais);
        txtTotal.textContent = fmt(montant + frais);
        warn.style.display  = 'none';
        btn.disabled        = false;
    } else if (montant > 0) {
        recapM.textContent  = fmt(montant);
        txtFrais.textContent = 'Hors barème';
        txtTotal.textContent = '—';
        warn.style.display  = '';
        btn.disabled        = true;
    } else {
        recapM.textContent  = '—';
        txtFrais.textContent = '—';
        txtTotal.textContent = '—';
        warn.style.display  = 'none';
        btn.disabled        = false;
    }
});
</script>
<?= $this->endSection() ?>
