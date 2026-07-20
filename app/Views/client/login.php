<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>MobiMoney — Connexion</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }

body {
    margin: 0;
    min-height: 100vh;
    background: #0f172a;
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

/* ── Cercles décoratifs (plats) ────────────────────────────── */
body::before, body::after {
    content: '';
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    opacity: .06;
}
body::before {
    width: 520px; height: 520px;
    background: #3b82f6;
    top: -140px; right: -140px;
}
body::after {
    width: 380px; height: 380px;
    background: #7c3aed;
    bottom: -100px; left: -100px;
}

/* ── Card ──────────────────────────────────────────────────── */
.login-card {
    background: #fff;
    border-radius: 1.25rem;
    padding: 2.25rem 2rem 1.75rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0,0,0,.28);
    position: relative;
    z-index: 1;
}

/* ── Logo ──────────────────────────────────────────────────── */
.brand-icon {
    width: 3.5rem; height: 3.5rem;
    background: #3b82f6;
    border-radius: .875rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: #fff;
    margin: 0 auto 1rem;
}

.login-title {
    font-size: 1.4rem; font-weight: 700; color: #0f172a;
    margin: 0 0 .2rem; text-align: center;
}

.login-subtitle {
    font-size: .875rem; color: #64748b;
    text-align: center; margin-bottom: 1.75rem;
}

/* ── Alerte erreur ─────────────────────────────────────────── */
.alert-login {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-left: 4px solid #ef4444;
    border-radius: .5rem;
    padding: .65rem .9rem;
    font-size: .8375rem; color: #b91c1c;
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: 1.25rem;
}

/* ── Étiquette champ ───────────────────────────────────────── */
.field-label {
    font-size: .8rem; font-weight: 600; color: #374151;
    display: block; margin-bottom: .45rem;
}

/* ── Groupe préfixe + numéro ───────────────────────────────── */
.phone-group {
    display: flex;
    border: 1.5px solid #e2e8f0;
    border-radius: .6rem;
    overflow: hidden;
    transition: border-color .15s, box-shadow .15s;
    background: #fff;
}

.phone-group:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.14);
}

/* Dropdown préfixe */
.prefix-select {
    flex-shrink: 0;
    border: none;
    border-right: 1.5px solid #e2e8f0;
    background: #f8fafc;
    color: #0f172a;
    font-family: inherit;
    font-size: .9rem;
    font-weight: 700;
    padding: 0 .5rem 0 .875rem;
    height: 2.875rem;
    outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    /* flèche custom */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394a3b8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right .5rem center;
    padding-right: 1.75rem;
    min-width: 4.75rem;
}

.prefix-select:focus { background-color: #eff6ff; }

/* Champ numéro (reste) */
.number-input {
    flex: 1;
    border: none;
    height: 2.875rem;
    padding: 0 1rem;
    font-size: .9375rem;
    color: #0f172a;
    font-family: inherit;
    outline: none;
    background: transparent;
    min-width: 0;
}

.number-input::placeholder { color: #b0bec5; }

/* Prévisualisation numéro complet */
.phone-preview {
    display: flex;
    align-items: center;
    gap: .4rem;
    margin-top: .5rem;
    font-size: .8rem;
    color: #64748b;
    min-height: 1.25rem;
}

.phone-preview .preview-val {
    font-weight: 600;
    color: #3b82f6;
}

/* ── Bouton continuer ──────────────────────────────────────── */
.btn-login {
    width: 100%;
    height: 2.875rem;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: .6rem;
    font-size: .9375rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1.25rem;
    font-family: inherit;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    transition: background .15s;
    -webkit-tap-highlight-color: transparent;
}

.btn-login:hover { background: #2563eb; }

/* ── Séparateur ────────────────────────────────────────────── */
.divider {
    display: flex; align-items: center; gap: .75rem;
    margin: 1.5rem 0 1rem;
    color: #cbd5e1; font-size: .8rem;
}
.divider::before, .divider::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}

/* ── Lien opérateur ────────────────────────────────────────── */
.admin-link {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .7rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: .6rem;
    color: #475569; text-decoration: none;
    font-size: .875rem; font-weight: 500;
    transition: border-color .15s, background .15s, color .15s;
}

.admin-link:hover {
    border-color: #7c3aed; color: #7c3aed; background: #f5f3ff;
}

.admin-icon {
    width: 1.75rem; height: 1.75rem;
    background: #f5f3ff; border-radius: .375rem;
    display: flex; align-items: center; justify-content: center;
    color: #7c3aed; font-size: .875rem; flex-shrink: 0;
}

.chevron-right {
    margin-left: auto; font-size: .7rem; color: #cbd5e1;
}

/* ── Footer ────────────────────────────────────────────────── */
.login-footer {
    text-align: center; margin-top: 1.5rem;
    font-size: .72rem; color: #94a3b8;
}
</style>
</head>
<body>

<div class="login-card">

    <!-- Logo -->
    <div class="brand-icon">
        <i class="bi bi-wallet2"></i>
    </div>

    <h1 class="login-title">MobiMoney</h1>
    <p class="login-subtitle">Entrez votre numéro pour accéder à votre compte</p>

    <!-- Erreur -->
    <?php if (session()->getFlashdata('erreur')): ?>
    <div class="alert-login">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= esc(session()->getFlashdata('erreur')) ?>
    </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="post" action="<?= base_url('login') ?>" id="loginForm">
        <?= csrf_field() ?>

        <!-- Champ caché — combinaison préfixe + numéro -->
        <input type="hidden" name="numero_telephone" id="numero_telephone">

        <label class="field-label" for="reste">Numéro de téléphone</label>

        <div class="phone-group">

            <!-- Dropdown des préfixes -->
            <select id="prefixSelect" class="prefix-select" aria-label="Préfixe réseau">
                <?php if (empty($prefixes)): ?>
                    <option value="">—</option>
                <?php else: ?>
                    <?php foreach ($prefixes as $p): ?>
                    <option value="<?= esc($p['prefixe']) ?>"><?= esc($p['prefixe']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <!-- Reste du numéro -->
            <input
                type="tel"
                id="reste"
                class="number-input"
                placeholder="12 345 67"
                inputmode="numeric"
                maxlength="10"
                autocomplete="tel-national"
            >
        </div>

        <!-- Prévisualisation en temps réel -->
        <div class="phone-preview" id="preview" style="display:none;">
            <i class="bi bi-phone" style="font-size:.8rem;"></i>
            Numéro complet :
            <span class="preview-val" id="previewVal"></span>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-arrow-right-circle"></i>
            Continuer
        </button>
    </form>

    <!-- Lien opérateur -->
    <div class="divider">ou</div>

    <a href="<?= base_url('operateur/login') ?>" class="admin-link">
        <span class="admin-icon"><i class="bi bi-shield-lock"></i></span>
        <span>Se connecter en tant qu'opérateur</span>
        <i class="bi bi-chevron-right chevron-right"></i>
    </a>

    <p class="login-footer">MobiMoney &copy; <?= date('Y') ?> — Accès sécurisé</p>

</div>

<script>
(function () {
    const prefixSel = document.getElementById('prefixSelect');
    const resteInput = document.getElementById('reste');
    const hiddenField = document.getElementById('numero_telephone');
    const preview = document.getElementById('preview');
    const previewVal = document.getElementById('previewVal');
    const form = document.getElementById('loginForm');

    function updatePreview() {
        const p = prefixSel.value || '';
        const r = resteInput.value.replace(/\s/g, '');
        const full = p + r;

        hiddenField.value = full;

        if (full.length > p.length) {
            previewVal.textContent = p + ' ' + resteInput.value.trim();
            preview.style.display = '';
        } else {
            preview.style.display = 'none';
        }
    }

    prefixSel.addEventListener('change', updatePreview);
    resteInput.addEventListener('input', function () {
        // Formatage léger : espaces tous les 2 chiffres après le préfixe
        let raw = this.value.replace(/\D/g, '').slice(0, 8);
        this.value = raw.replace(/(\d{2})(?=\d)/g, '$1 ').trim();
        updatePreview();
    });

    form.addEventListener('submit', function (e) {
        const p = prefixSel.value || '';
        const r = resteInput.value.replace(/\s/g, '');

        if (!p || !r) {
            e.preventDefault();
            resteInput.focus();
            return;
        }

        hiddenField.value = p + r;
    });

    // Init
    updatePreview();
})();
</script>

</body>
</html>
