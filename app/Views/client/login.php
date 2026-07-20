<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MobiMoney — Connexion</title>

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

    /* ── Décoration de fond (cercles plats, pas de dégradé) ── */
    body::before,
    body::after {
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

    /* ── Card principale ───────────────────────────────────── */
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

    /* ── Logo / icône ─────────────────────────────────────── */
    .brand-icon {
        width: 3.5rem;
        height: 3.5rem;
        background: #3b82f6;
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

    /* ── Champs ───────────────────────────────────────────── */
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
    }

    .input-icon-wrap input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.14);
    }

    .input-icon-wrap input::placeholder { color: #b0bec5; }

    /* ── Bouton principal ─────────────────────────────────── */
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
        transition: background .15s;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
    }

    .btn-login:hover { background: #2563eb; }

    /* ── Alerte erreur ────────────────────────────────────── */
    .alert-login {
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

    /* ── Séparateur ───────────────────────────────────────── */
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

    /* ── Lien opérateur ───────────────────────────────────── */
    .admin-link {
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

    .admin-link:hover {
        border-color: #7c3aed;
        color: #7c3aed;
        background: #f5f3ff;
    }

    .admin-link .admin-icon {
        width: 1.75rem;
        height: 1.75rem;
        background: #f5f3ff;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7c3aed;
        font-size: .875rem;
        flex-shrink: 0;
    }

    /* ── Footer ───────────────────────────────────────────── */
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
        <i class="bi bi-wallet2"></i>
    </div>

    <h1 class="login-title">MobiMoney</h1>
    <p class="login-subtitle">Connectez-vous avec votre numéro de téléphone</p>

    <!-- Erreur -->
    <?php if (session()->getFlashdata('erreur')): ?>
    <div class="alert-login">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= esc(session()->getFlashdata('erreur')) ?>
    </div>
    <?php endif; ?>

    <!-- Formulaire client -->
    <form method="post" action="<?= base_url('login') ?>">
        <?= csrf_field() ?>

        <label class="field-label" for="numero_telephone">Numéro de téléphone</label>
        <div class="input-icon-wrap">
            <i class="bi bi-phone"></i>
            <input
                type="tel"
                id="numero_telephone"
                name="numero_telephone"
                placeholder="Ex : 033 12 345 67"
                autocomplete="tel"
                required
            >
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-arrow-right-circle"></i>
            Continuer
        </button>
    </form>

    <!-- Lien accès opérateur -->
    <div class="divider">ou</div>

    <a href="<?= base_url('operateur/login') ?>" class="admin-link">
        <span class="admin-icon"><i class="bi bi-shield-lock"></i></span>
        <span>Se connecter en tant qu'opérateur</span>
        <i class="bi bi-chevron-right ms-auto" style="font-size:.75rem;color:#cbd5e1"></i>
    </a>

    <p class="login-footer">MobiMoney &copy; <?= date('Y') ?> — Accès sécurisé</p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
