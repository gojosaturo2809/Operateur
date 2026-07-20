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
                    maxlength="14"
                    required
                >
            </div>

            <div class="form-check mt-3 mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="inclureFraisRetrait" name="inclure_frais_retrait">
                <label class="form-check-label" for="inclureFraisRetrait">Inclure les frais de retrait du destinataire</label>
            <!-- Badge réseau détecté -->
            <div id="badgeReseau" style="display:none;margin-top:-.5rem;margin-bottom:.9rem;">
                <span id="badgeReseauLabel"
                      style="display:inline-flex;align-items:center;gap:.375rem;
                             padding:.25rem .65rem;border-radius:2rem;font-size:.75rem;font-weight:600;">
                </span>
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

            <!-- Option frais de retrait -->
            <div id="optionFraisRetrait" style="margin:.75rem 0 .5rem;">
                <label style="display:flex;align-items:flex-start;gap:.6rem;cursor:pointer;">
                    <input type="checkbox" value="1"
                           id="inclureFraisRetrait" name="inclure_frais_retrait"
                           style="margin-top:.2rem;accent-color:#3b82f6;
                                  width:1.1rem;height:1.1rem;flex-shrink:0;">
                    <div>
                        <span style="font-size:.875rem;font-weight:600;color:#0f172a;">
                            Prendre en charge les frais de retrait
                        </span>
                        <p style="font-size:.75rem;color:#64748b;margin:.15rem 0 0;">
                            Le destinataire pourra retirer sans payer de frais supplémentaires.
                        </p>
                    </div>
                </label>
            </div>

            <!-- Récapitulatif -->
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
                    <span>Total débité</span>
                    <span id="txtTotal" style="color:#3b82f6;">—</span>
                </div>
            </div>

            <div id="warnBareme" class="client-warn" style="display:none;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span id="warnMessage">Montant hors barème — transfert indisponible.</span>
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
const baremes = <?= $baremes ?? '[]' ?>;
const baremesRetrait = <?= $baremesRetrait ?? '[]' ?>;
const prefixesOperateurs = <?= $prefixesOperateurs ?? '[]' ?>;
const inputMontant = document.getElementById('montantInput');
const inputDest = document.getElementById('numero_destinataire');
const checkFraisRetrait = document.getElementById('inclureFraisRetrait');
const recapMontant = document.getElementById('recapMontant');
const txtFrais = document.getElementById('txtFrais');
const txtTotal = document.getElementById('txtTotal');
const warn = document.getElementById('warnBareme');
const warnMessage = document.getElementById('warnMessage');
const btn = document.getElementById('btnSubmit');
const badgeReseau = document.getElementById('badgeReseau');
const badgeReseauLabel = document.getElementById('badgeReseauLabel');
const ligneCommission = document.getElementById('ligneCommission');
const txtCommission = document.getElementById('txtCommission');
const ligneFraisRetrait = document.getElementById('ligneFraisRetrait');
const txtFraisRetrait = document.getElementById('txtFraisRetrait');

const fmt = n => Number(n).toLocaleString('fr-FR') + ' Ar';
const trouverBareme = (liste, montant) => liste.find(b => montant >= Number(b.montant_min) && montant <= Number(b.montant_max));
const getOperateur = () => {
    const numero = inputDest.value.replace(/\D/g, '');
    return numero.length >= 3 ? prefixesOperateurs.find(p => p.prefixe === numero.slice(0, 3)) : null;
};

function recalculer() {
    const montant = Number(inputMontant.value) || 0;
    const numero = inputDest.value.replace(/\D/g, '');
    const operateur = getOperateur();
    const estTiers = operateur && Number(operateur.est_principal) !== 1;
    const baremeTransfert = montant > 0 ? trouverBareme(baremes, montant) : null;
    const baremeRetrait = montant > 0 ? trouverBareme(baremesRetrait, montant) : null;

    badgeReseau.style.display = operateur ? '' : 'none';
    if (operateur) {
        const estLocal = Number(operateur.est_principal) === 1;
        badgeReseauLabel.textContent = (estLocal ? 'Réseau local — ' : 'Réseau tiers — ') + operateur.operateur_nom;
        badgeReseauLabel.style.background = estLocal ? '#ecfdf5' : '#f5f3ff';
        badgeReseauLabel.style.color = estLocal ? '#059669' : '#7c3aed';
    }
    ligneCommission.style.display = 'none';
    ligneFraisRetrait.style.display = 'none';
    warn.style.display = 'none';

    // Les numéros mobiles pris en charge ont 10 chiffres, avec un préfixe configuré.
    if (numero.length === 0) {
        inputDest.setCustomValidity('Saisissez le numéro du destinataire.');
        btn.disabled = true;
        return;
    }
    if (numero.length !== 10) {
        inputDest.setCustomValidity('Le numéro doit contenir exactement 10 chiffres.');
        warnMessage.textContent = 'Le numéro du destinataire doit contenir 10 chiffres.';
        warn.style.display = ''; btn.disabled = true;
        return;
    }
    if (!operateur) {
        inputDest.setCustomValidity('Le préfixe du numéro n’est associé à aucun opérateur.');
        warnMessage.textContent = 'Préfixe non reconnu : choisissez un numéro d’un opérateur configuré.';
        warn.style.display = ''; btn.disabled = true;
        return;
    }
    inputDest.setCustomValidity('');

    if (montant <= 0) {
        recapMontant.textContent = txtFrais.textContent = txtTotal.textContent = '—';
        btn.disabled = true;
        return;
    }
    if (!baremeTransfert) {
        recapMontant.textContent = fmt(montant);
        txtFrais.textContent = 'Hors barème'; txtTotal.textContent = '—';
        warnMessage.textContent = 'Ce montant est hors barème — transfert indisponible.';
        warn.style.display = ''; btn.disabled = true;
        return;
    }

    const fraisEnvoi = Number(baremeTransfert.frais);
    const commission = estTiers ? Math.round(montant * Number(operateur.commission_inter_pct || 0)) / 100 : 0;
    const fraisRetrait = checkFraisRetrait.checked && baremeRetrait ? Number(baremeRetrait.frais) : 0;
    if (checkFraisRetrait.checked && !baremeRetrait) {
        warnMessage.textContent = 'Ce montant est hors barème de retrait.';
        warn.style.display = ''; btn.disabled = true;
        return;
    }
    if (commission > 0) { ligneCommission.style.display = ''; txtCommission.textContent = fmt(commission); }
    if (fraisRetrait > 0) { ligneFraisRetrait.style.display = ''; txtFraisRetrait.textContent = fmt(fraisRetrait); }
    recapMontant.textContent = fmt(montant);
    txtFrais.textContent = fmt(fraisEnvoi);
    txtTotal.textContent = fmt(montant + fraisEnvoi + commission + fraisRetrait);
    btn.disabled = false;
}

inputMontant.addEventListener('input', recalculer);
inputDest.addEventListener('input', recalculer);
checkFraisRetrait.addEventListener('change', recalculer);
recalculer();
</script>
<?= $this->endSection() ?>
