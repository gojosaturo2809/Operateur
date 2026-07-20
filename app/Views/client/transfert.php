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

            <!-- Option frais de retrait — visible uniquement sur réseau local -->
            <div id="optionFraisRetrait" style="display:none;margin:.75rem 0 .5rem;">
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
/* ── Données injectées depuis PHP ──────────────────────────────── */
const baremes            = <?= $baremes          ?? '[]' ?>;
const baremesRetrait     = <?= $baremesRetrait   ?? '[]' ?>;
const prefixesOperateurs = <?= $prefixesOperateurs ?? '[]' ?>;

/* ── Références DOM ────────────────────────────────────────────── */
const inputMontant        = document.getElementById('montantInput');
const inputDest           = document.getElementById('numero_destinataire');
const checkFraisRetrait   = document.getElementById('inclureFraisRetrait');
const blockFraisRetrait   = document.getElementById('optionFraisRetrait');
const badgeReseau         = document.getElementById('badgeReseau');
const badgeReseauLabel    = document.getElementById('badgeReseauLabel');

const elRecapMontant      = document.getElementById('recapMontant');
const elFrais             = document.getElementById('txtFrais');
const elLigneCommission   = document.getElementById('ligneCommission');
const elCommission        = document.getElementById('txtCommission');
const elLigneFraisRetrait = document.getElementById('ligneFraisRetrait');
const elFraisRetrait      = document.getElementById('txtFraisRetrait');
const elTotal             = document.getElementById('txtTotal');
const elWarn              = document.getElementById('warnBareme');
const elWarnMsg           = document.getElementById('warnMessage');
const btn                 = document.getElementById('btnSubmit');

/* ── Helpers ───────────────────────────────────────────────────── */
function fmt(n) {
    return n.toLocaleString('fr-FR') + ' Ar';
}

function trouverBareme(liste, montant) {
    return liste.find(
        b => montant >= parseFloat(b.montant_min) && montant <= parseFloat(b.montant_max)
    ) || null;
}

function afficherWarn(msg) {
    elWarnMsg.textContent = msg;
    elWarn.style.display  = '';
}

function cacherWarn() {
    elWarn.style.display = 'none';
}

function getOperateur() {
    /* Extrait les chiffres, prend les 3 premiers pour le préfixe */
    const num = inputDest.value.replace(/\D/g, '');
    if (num.length < 3) return null;
    return prefixesOperateurs.find(p => p.prefixe === num.slice(0, 3)) || null;
}

/* ── Recalcul principal ────────────────────────────────────────── */
function recalculer() {
    const montant  = parseFloat(inputMontant.value) || 0;
    const operateur = getOperateur();
    const estLocal  = operateur !== null && Number(operateur.est_principal) === 1;
    const estTiers  = operateur !== null && !estLocal;

    /* ─ Badge réseau ─────────────────────────────────────────── */
    if (operateur) {
        badgeReseau.style.display = '';
        if (estLocal) {
            badgeReseauLabel.style.cssText =
                'display:inline-flex;align-items:center;gap:.375rem;padding:.25rem .65rem;' +
                'border-radius:2rem;font-size:.75rem;font-weight:600;' +
                'background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;';
            badgeReseauLabel.innerHTML =
                '<i class="bi bi-house-fill" style="font-size:.65rem;"></i>' +
                ' Réseau local — ' + operateur.operateur_nom;
        } else {
            badgeReseauLabel.style.cssText =
                'display:inline-flex;align-items:center;gap:.375rem;padding:.25rem .65rem;' +
                'border-radius:2rem;font-size:.75rem;font-weight:600;' +
                'background:#f5f3ff;color:#7c3aed;border:1px solid #ddd6fe;';
            badgeReseauLabel.innerHTML =
                '<i class="bi bi-broadcast" style="font-size:.65rem;"></i>' +
                ' Réseau tiers — ' + operateur.operateur_nom;
        }
    } else {
        badgeReseau.style.display = 'none';
    }

    /* ─ Option frais de retrait : visible seulement réseau local ─ */
    blockFraisRetrait.style.display = estLocal ? '' : 'none';
    if (!estLocal) {
        checkFraisRetrait.checked = false;
    }

    /* ─ Reset lignes récap ───────────────────────────────────── */
    elLigneCommission.style.display   = 'none';
    elLigneFraisRetrait.style.display = 'none';
    cacherWarn();

    /* ─ Pas de montant ───────────────────────────────────────── */
    if (montant <= 0) {
        elRecapMontant.textContent = '—';
        elFrais.textContent        = '—';
        elTotal.textContent        = '—';
        btn.disabled               = false;
        return;
    }

    /* ─ Barème de transfert ──────────────────────────────────── */
    const baremeTransfert = trouverBareme(baremes, montant);
    if (!baremeTransfert) {
        elRecapMontant.textContent = fmt(montant);
        elFrais.textContent        = 'Hors barème';
        elTotal.textContent        = '—';
        afficherWarn('Ce montant est hors barème — transfert indisponible.');
        btn.disabled = true;
        return;
    }

    const fraisEnvoi = parseFloat(baremeTransfert.frais);

    /* ─ Commission inter-opérateur ───────────────────────────── */
    let commission = 0;
    if (estTiers) {
        const taux = parseFloat(operateur.commission_inter_pct) || 0;
        /* Même calcul que le serveur : round(montant * taux / 100, 2) */
        commission = Math.round(montant * taux) / 100;
        if (commission > 0) {
            elLigneCommission.style.display = '';
            elCommission.textContent        = fmt(commission);
        }
    }

    /* ─ Frais de retrait pris en charge ─────────────────────── */
    let fraisRetrait = 0;
    if (checkFraisRetrait.checked && estLocal) {
        const baremeRetrait = trouverBareme(baremesRetrait, montant);
        if (!baremeRetrait) {
            /* Hors barème de retrait : tout afficher puis bloquer */
            elRecapMontant.textContent        = fmt(montant);
            elFrais.textContent               = fmt(fraisEnvoi);
            elLigneFraisRetrait.style.display = '';
            elFraisRetrait.textContent        = 'Hors barème';
            elTotal.textContent               = '—';
            afficherWarn("Ce montant est hors barème de retrait — décochez l'option ou changez le montant.");
            btn.disabled = true;
            return;
        }
        fraisRetrait = parseFloat(baremeRetrait.frais);
        elLigneFraisRetrait.style.display = '';
        elFraisRetrait.textContent        = fmt(fraisRetrait);
    }

    /* ─ Tout est OK : afficher le récap complet ─────────────── */
    elRecapMontant.textContent = fmt(montant);
    elFrais.textContent        = fmt(fraisEnvoi);
    elTotal.textContent        = fmt(montant + fraisEnvoi + commission + fraisRetrait);
    btn.disabled               = false;
}

/* ── Écouteurs ─────────────────────────────────────────────────── */
inputMontant.addEventListener('input', recalculer);
inputDest.addEventListener('input', recalculer);
checkFraisRetrait.addEventListener('change', recalculer);

/* Init */
recalculer();
</script>
<?= $this->endSection() ?>
