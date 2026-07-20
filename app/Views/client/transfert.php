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

            <div id="optionFraisRetrait" class="form-check mt-3 mb-3">
                <input class="form-check-input" type="checkbox" value="1"
                       id="inclureFraisRetrait" name="inclure_frais_retrait">
                <label class="form-check-label" for="inclureFraisRetrait">
                    Prendre en charge les frais de retrait du destinataire
                </label>
                <div class="form-text">Le destinataire recevra le montant net. Disponible uniquement sur le réseau principal.</div>
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
                <div class="client-recap-row" id="ligneCommission" style="display:none;">
                    <span>Commission inter-opérateur</span>
                    <span id="txtCommission" style="color:#f59e0b;font-weight:600;">—</span>
                </div>
                <div class="client-recap-row" id="ligneFraisRetrait" style="display:none;">
                    <span>Frais de retrait pris en charge</span>
                    <span id="txtFraisRetrait" style="color:#f59e0b;font-weight:600;">—</span>
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
const prefixesOperateurs = <?= $prefixesOperateurs ?? '[]' ?>;
const input    = document.getElementById('montantInput');
const destinataire = document.getElementById('numero_destinataire');
const inclureFraisRetrait = document.getElementById('inclureFraisRetrait');
const optionFraisRetrait = document.getElementById('optionFraisRetrait');
const recapM   = document.getElementById('recapMontant');
const txtFrais = document.getElementById('txtFrais');
const ligneCommission = document.getElementById('ligneCommission');
const txtCommission = document.getElementById('txtCommission');
const ligneFraisRetrait = document.getElementById('ligneFraisRetrait');
const txtFraisRetrait = document.getElementById('txtFraisRetrait');
const txtTotal = document.getElementById('txtTotal');
const warn     = document.getElementById('warnBareme');
const btn      = document.getElementById('btnSubmit');

function fmt(n) { return n.toLocaleString('fr-FR') + ' Ar'; }

function operateurDestination() {
    const numero = destinataire.value.replace(/\D/g, '');
    return prefixesOperateurs.find(p => p.prefixe === numero.slice(0, 3)) || null;
}

function recalculer() {
    const montant = parseFloat(input.value) || 0;
    let frais = 0, trouve = false, fraisRetrait = 0;
    const operateur = operateurDestination();
    const reseauPrincipal = operateur && Number(operateur.est_principal) === 1;

    // Hors réseau principal, l'option est désactivée et ne sera pas envoyée.
    // La case reste disponible tant qu'un préfixe n'est pas encore reconnu.
    // Elle n'est désactivée que lorsqu'un opérateur tiers est identifié.
    const estOperateurTiers = operateur && !reseauPrincipal;
    inclureFraisRetrait.disabled = estOperateurTiers;
    if (estOperateurTiers) inclureFraisRetrait.checked = false;
    optionFraisRetrait.style.opacity = estOperateurTiers ? '.55' : '1';
    ligneCommission.style.display = 'none';
    ligneFraisRetrait.style.display = 'none';

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
        if (inclureFraisRetrait.checked && reseauPrincipal) {
            const retrait = baremesRetrait.find(b => montant >= b.montant_min && montant <= b.montant_max);
            if (!retrait) {
                txtFraisRetrait.textContent = 'Hors barème';
                ligneFraisRetrait.style.display = '';
                warn.textContent = 'Montant hors barème de retrait — transfert indisponible.';
                warn.style.display = '';
                btn.disabled = true;
                return;
            }
            fraisRetrait = parseFloat(retrait.frais);
        }
        recapM.textContent   = fmt(montant);
        txtFrais.textContent = fmt(frais);
        const commission = operateur && !reseauPrincipal
            ? Math.round(montant * (Number(operateur.commission_inter_pct) || 0)) / 100
            : 0;
        ligneCommission.style.display = commission > 0 ? '' : 'none';
        txtCommission.textContent = fmt(commission);
        ligneFraisRetrait.style.display = inclureFraisRetrait.checked && reseauPrincipal ? '' : 'none';
        txtFraisRetrait.textContent = fmt(fraisRetrait);
        txtTotal.textContent = fmt(montant + frais + commission + fraisRetrait);
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
}

input.addEventListener('input', recalculer);
destinataire.addEventListener('input', recalculer);
inclureFraisRetrait.addEventListener('change', recalculer);
recalculer();
</script>
<?= $this->endSection() ?>
