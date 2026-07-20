<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('client/dashboard') ?>" class="client-back">
    <i class="bi bi-arrow-left"></i> Retour
</a>

<div class="client-form-card">

    <div class="client-form-header">
        <span class="client-form-icon" style="background:#eff6ff;color:#3b82f6;">
            <i class="bi bi-send"></i>
        </span>
        <div>
            <p class="client-form-title">Transfert</p>
            <p class="client-form-sub">Envoyer de l'argent — frais selon barème</p>
        </div>
    </div>

    <div class="client-form-body">

        <?php if (session()->getFlashdata('erreur')): ?>
        <div class="client-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= esc(session()->getFlashdata('erreur')) ?>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('client/store-transfert') ?>">
            <?= csrf_field() ?>

            <!-- Destinataire -->
            <label class="client-field-label" for="numero_destinataire">Numéro du destinataire</label>
            <div class="client-input-wrap">
                <i class="bi bi-phone"></i>
                <input
                    type="tel"
                    id="numero_destinataire"
                    name="numero_destinataire"
                    class="client-input"
                    placeholder="Ex : 033 12 345 67"
                    autocomplete="tel"
                    inputmode="tel"
                    required
                >
            </div>

            <div class="form-check mt-3 mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="inclureFraisRetrait" name="inclure_frais_retrait">
                <label class="form-check-label" for="inclureFraisRetrait">Inclure les frais de retrait du destinataire</label>
            </div>

            <!-- Montant -->
            <label class="client-field-label" for="montantInput">Montant à envoyer</label>
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
                    <span>Montant envoyé</span>
                    <span id="recapMontant" style="color:#64748b;">—</span>
                </div>
                <div class="client-recap-row">
                    <span>Frais d'envoi</span>
                    <span id="txtFrais" style="color:#f59e0b;font-weight:600;">—</span>
                </div>
                <div class="client-recap-row client-recap-total">
                    <span>Total opération</span>
                    <span id="txtTotal" style="color:#3b82f6;">—</span>
                </div>
            </div>

            <div id="warnBareme" class="client-warn" style="display:none;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Montant hors barème — transfert indisponible.
            </div>

            <button type="submit" id="btnSubmit" class="client-btn" style="background:#3b82f6;">
                <i class="bi bi-send-fill"></i>
                Envoyer maintenant
            </button>
        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const baremes  = <?= $baremes ?? '[]' ?>;
const baremesRetrait = <?= $baremesRetrait ?? '[]' ?>;
const input    = document.getElementById('montantInput');
const recapM   = document.getElementById('recapMontant');
const txtFrais = document.getElementById('txtFrais');
const txtTotal = document.getElementById('txtTotal');
const warn     = document.getElementById('warnBareme');
const btn      = document.getElementById('btnSubmit');
const inclureRetrait = document.getElementById('inclureFraisRetrait');

function fmt(n) { return n.toLocaleString('fr-FR') + ' Ar'; }

input.addEventListener('input', function () {
    const montant = parseFloat(this.value) || 0;
    let frais = 0, fraisRetrait = 0, trouve = false;

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
        if (inclureRetrait.checked) {
            const retrait = baremesRetrait.find(b => montant >= b.montant_min && montant <= b.montant_max);
            if (!retrait) { warn.style.display = ''; btn.disabled = true; return; }
            fraisRetrait = parseFloat(retrait.frais);
        }
        recapM.textContent   = fmt(montant);
        txtFrais.textContent = fmt(frais + fraisRetrait);
        txtTotal.textContent = fmt(montant + frais + fraisRetrait);
        warn.style.display   = 'none';
        btn.disabled         = false;
    } else if (montant > 0) {
        recapM.textContent   = fmt(montant);
        txtFrais.textContent = 'Hors barème';
        txtTotal.textContent = '—';
        warn.style.display   = '';
        btn.disabled         = true;
    } else {
        recapM.textContent   = '—';
        txtFrais.textContent = '—';
        txtTotal.textContent = '—';
        warn.style.display   = 'none';
        btn.disabled         = false;
    }
});
inclureRetrait.addEventListener('change', () => input.dispatchEvent(new Event('input')));
</script>
<?= $this->endSection() ?>
