<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('client/dashboard') ?>" class="client-back">
    <i class="bi bi-arrow-left"></i> Retour
</a>

<div class="client-form-card">
    <div class="client-form-header">
        <span class="client-form-icon" style="background:#f3e8ff;color:#9333ea;">
            <i class="bi bi-people-fill"></i>
        </span>
        <div>
            <p class="client-form-title">Envoi multiple divisé</p>
            <p class="client-form-sub">Un montant réparti équitablement entre vos destinataires</p>
        </div>
    </div>

    <div class="client-form-body">
        <div class="client-warn" style="display:flex;margin-bottom:1rem;">
            <i class="bi bi-info-circle-fill"></i>
            Seuls les numéros du réseau principal sont acceptés.
        </div>

        <form method="post" action="<?= base_url('client/storeEnvoiMultiple') ?>">
            <?= csrf_field() ?>

            <label class="client-field-label" for="montantGlobal">Montant global à répartir</label>
            <div class="client-amount-wrap">
                <input type="number" id="montantGlobal" name="montant_global"
                       class="client-amount-input" placeholder="0" min="1" step="1"
                       inputmode="numeric" value="<?= esc(old('montant_global')) ?>" required>
                <span class="client-amount-unit">Ar</span>
            </div>

            <label class="client-field-label" for="numerosBruts">Numéros destinataires</label>
            <textarea id="numerosBruts" name="numeros_bruts" class="client-input"
                      style="height:8rem;padding:.8rem;resize:vertical;"
                      placeholder="Saisir les numéros séparés par une virgule ou un retour à la ligne"
                      inputmode="tel" required><?= esc(old('numeros_bruts')) ?></textarea>

            <div id="preview" class="client-recap" aria-live="polite">
                <div class="client-recap-row">
                    <span>Destinataires détectés</span>
                    <span id="nbNumeros" style="font-weight:600;color:#9333ea;">0 numéro</span>
                </div>
                <div class="client-recap-row">
                    <span>Montant par personne</span>
                    <span id="montantParPersonne">—</span>
                </div>
                <div class="client-recap-row">
                    <span>Frais estimés</span>
                    <span id="fraisTotal" style="color:#f59e0b;font-weight:600;">—</span>
                </div>
                <div class="client-recap-row client-recap-total">
                    <span>Coût total estimé</span>
                    <span id="coutTotal" style="color:#9333ea;">—</span>
                </div>
            </div>

            <div id="validationMessage" class="client-warn" style="display:none;"></div>

            <button type="submit" id="btnEnvoyer" class="client-btn" style="background:#9333ea;">
                <i class="bi bi-share-fill"></i>
                Répartir et envoyer
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const baremes = <?= $baremes ?? '[]' ?>;
const montantInput = document.getElementById('montantGlobal');
const numerosInput = document.getElementById('numerosBruts');
const nbNumeros = document.getElementById('nbNumeros');
const montantParPersonne = document.getElementById('montantParPersonne');
const fraisTotal = document.getElementById('fraisTotal');
const coutTotal = document.getElementById('coutTotal');
const message = document.getElementById('validationMessage');
const bouton = document.getElementById('btnEnvoyer');

function formatAr(valeur) {
    return valeur.toLocaleString('fr-FR', { maximumFractionDigits: 2 }) + ' Ar';
}

function afficherMessage(texte) {
    message.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> ' + texte;
    message.style.display = 'flex';
}

function mettreAJourApercu() {
    const montant = Number(montantInput.value) || 0;
    const numeros = numerosInput.value.trim().split(/[\s,;]+/).filter(Boolean).map(n => n.replace(/\D/g, ''));
    const uniques = new Set(numeros);
    const nombre = numeros.length;
    const doublon = uniques.size !== nombre;

    nbNumeros.textContent = nombre + (nombre > 1 ? ' numéros' : ' numéro');
    montantParPersonne.textContent = '—';
    fraisTotal.textContent = '—';
    coutTotal.textContent = '—';
    message.style.display = 'none';
    bouton.disabled = false;

    if (nombre > 0 && doublon) {
        afficherMessage('Un ou plusieurs numéros sont présents plusieurs fois.');
        bouton.disabled = true;
        return;
    }
    if (nombre > 0 && nombre < 2) {
        afficherMessage('Au moins deux numéros sont requis.');
        bouton.disabled = true;
        return;
    }
    if (montant <= 0 || nombre < 2) return;

    const part = montant / nombre;
    const bareme = baremes.find(b => part >= Number(b.montant_min) && part <= Number(b.montant_max));
    montantParPersonne.textContent = formatAr(part);
    if (!bareme) {
        afficherMessage('Le montant par personne est hors barème de transfert.');
        bouton.disabled = true;
        return;
    }

    const frais = Number(bareme.frais) * nombre;
    fraisTotal.textContent = formatAr(frais);
    coutTotal.textContent = formatAr(montant + frais);
}

montantInput.addEventListener('input', mettreAJourApercu);
numerosInput.addEventListener('input', mettreAJourApercu);
mettreAJourApercu();
</script>
<?= $this->endSection() ?>
