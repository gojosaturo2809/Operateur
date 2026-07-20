<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Inscription<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="page-title">Créer un compte</h1>
<p class="page-sub">Rejoignez le blog en quelques secondes.</p>

<div class="card form-card">
    <form method="post" action="/register">
        <div class="field">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="vous@exemple.mg" required>
        </div>
        <div class="field">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
    </form>
    <p class="muted-note">Déjà inscrit ? <a href="/login">Se connecter</a></p>
</div>
<?= $this->endSection() ?>
