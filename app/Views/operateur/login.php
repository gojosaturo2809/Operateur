<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MobiMoney — Accès Opérateur</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    /* ── Décoration de fond ────────────────────────────────── */
    body::before,
    body::after {
        content: '';
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        opacity: .06;
    }
    body::before {
        width: 480px; height: 480px;
        background: #7c3aed;
        top: -120px; left: -120px;
    }
    body::after {
        width: 340px; height: 340px;
        background: #3b82f6;
        bottom: -80px; right: -80px;
    }

    /* ── Card ──────────────────────────────────────────────── */
    .login-card {
        background: #ffffff;
        border-radius: 1.25rem;
        padding: 2.5rem 2.25rem 2rem;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 8px 32px rgba(0,0,0,.28);
        position: relative;
        z-index: 1;
    }

    /* ── Logo ──────────────────────────────────────────────── */
    .brand-icon {
        width: 3.5rem;
        height: 3.5rem;
        background: #7c3aed;
        border-radius: .875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        margin: 0 auto 1rem;
    }

    .login-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .25rem;
        text-align: center;
    }

    .login-subtitle {
        font-size: .875rem;
        color: #64748b;
        text-align: center;
        margin-bottom: 1.75rem;
    }

    /* ── Badge "Espace opérateur" ──────────────────────────── */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        border-radius: 2rem;
        padding: .3rem .85rem;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin: 0 auto 1.5rem;
        display: flex;
        justify-content: center;
    }

    /* ── Champs ────────────────────────────────────────────── */
    .field-group { margin-bottom: 1rem; }

    .field-label {
        font-size: .8125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .4rem;
        display: block;
    }

    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .bi {
        position: absolute;
        left: .875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        z-index: 2;
    }

    .input-icon-wrap input {
        padding-left: 2.6rem;
        border: 1.5px solid #e2e8f0;
        border-radius: .6rem;
        font-size: .9375rem;
        height: 2.875rem;
        width: 100%;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        font-family: inherit;
        background: #fff;
    }

    .input-icon-wrap input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124,58,237,.13);
    }

    .input-icon-wrap input::placeholder { color: #b0bec5; }

    /* ── Toggle password ───────────────────────────────────── */
    .toggle-pw {
        position: absolute;
        right: .875rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1rem;
        z-index: 2;
        line-height: 1;
    }
    .toggle-pw:hover { color: #475569; }

    .input-icon-wrap input.has-toggle { padding-right: 2.6rem; }

    /* ── Bouton ────────────────────────────────────────────── */
    .btn-login {
        width: 100%;
        height: 2.875rem;
        background: #7c3aed;
        color: #fff;
        border: none;
        border-radius: .6rem;
        font-size: .9375rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: .5rem;
        transition: background .15s;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
    }

    .btn-login:hover { background: #6d28d9; }

    /* ── Alertes ───────────────────────────────────────────── */
    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 4px solid #ef4444;
        border-radius: .5rem;
        padding: .65rem .9rem;
        font-size: .8375rem;
        color: #b91c1c;
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1.25rem;
    }

    .alert-success-msg {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-left: 4px solid #10b981;
        border-radius: .5rem;
        padding: .65rem .9rem;
        font-size: .8375rem;
        color: #065f46;
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1.25rem;
    }

    /* ── Séparateur ────────────────────────────────────────── */
    .divider {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin: 1.5rem 0 1rem;
        color: #cbd5e1;
        font-size: .8rem;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    /* ── Lien retour client ────────────────────────────────── */
    .client-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .7rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: .6rem;
        color: #475569;
        text-decoration: none;
        font-size: .875rem;
        font-weight: 500;
        transition: border-color .15s, background .15s, color .15s;
    }

    .client-link:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #eff6ff;
    }

    /* ── Footer ────────────────────────────────────────────── */
    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: .75rem;
        color: #94a3b8;
    }
</style>
</head>
<body>

<div class="login-card">

    <!-- Logo -->
    <div class="brand-icon">
        <i class="bi bi-shield-lock-fill"></i>
    </div>

    <h1 class="login-title">MobiMoney</h1>

    <div class="d-flex justify-content-center mb-3">
        <div class="role-badge">
            <i class="bi bi-star-fill"></i>
            Espace opérateur
        </div>
    </div>

    <p class="login-subtitle">Connectez-vous avec vos identifiants d'administration</p>

    <!-- Erreur -->
    <?php if (session()->getFlashdata('erreur')): ?>
    <div class="alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= esc(session()->getFlashdata('erreur')) ?>
    </div>
    <?php endif; ?>

    <!-- Succès (ex: après création de compte) -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert-success-msg">
        <i class="bi bi-check-circle-fill"></i>
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
    <?php endif; ?>

    <!-- Formulaire opérateur -->
    <form method="post" action="<?= base_url('operateur/login') ?>">
        <?= csrf_field() ?>

        <div class="field-group">
            <label class="field-label" for="nom_utilisateur">Nom d'utilisateur</label>
            <div class="input-icon-wrap">
                <i class="bi bi-person"></i>
                <input
                    type="text"
                    id="nom_utilisateur"
                    name="nom_utilisateur"
                    placeholder="Identifiant opérateur"
                    value="<?= esc(old('nom_utilisateur')) ?>"
                    autocomplete="username"
                    required
                >
            </div>
        </div>

        <div class="field-group">
            <label class="field-label" for="mot_de_passe">Mot de passe</label>
            <div class="input-icon-wrap">
                <i class="bi bi-lock"></i>
                <input
                    type="password"
                    id="mot_de_passe"
                    name="mot_de_passe"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    class="has-toggle"
                    required
                >
                <button type="button" class="toggle-pw" id="togglePw" title="Afficher/masquer">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i>
            Se connecter
        </button>
    </form>

    <!-- Retour client -->
    <div class="divider">ou</div>

    <a href="<?= base_url('login') ?>" class="client-link">
        <i class="bi bi-chevron-left" style="font-size:.75rem"></i>
        <span>Retour à la connexion client</span>
    </a>

    <p class="login-footer">MobiMoney &copy; <?= date('Y') ?> — Accès réservé aux opérateurs</p>

</div>

<script>
    // Toggle visibilité du mot de passe
    const toggleBtn  = document.getElementById('togglePw');
    const pwInput    = document.getElementById('mot_de_passe');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleBtn.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type   = isHidden ? 'text' : 'password';
        toggleIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
</script>

</body>
</html>
