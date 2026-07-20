<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Connexion<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="page-title">Connexion</h1>
<p class="page-sub">Accédez à votre espace.</p>

<div class="card form-card">
    <form method="post" action="/login">
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="vous@exemple.mg" required>
        </div>
        <div class="field">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
    </form>
    <p class="muted-note">Pas de compte ? <a href="/register">Créer un compte</a></p>
</div>
<?= $this->endSection() ?>
