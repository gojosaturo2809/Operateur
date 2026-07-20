<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Nouvel article<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="page-title">Nouvel article</h1>
<p class="page-sub">Rédigez et publiez un nouvel article.</p>

<div class="card form-card">
    <form method="post" action="/articles/store">
        <div class="field">
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" placeholder="Un titre accrocheur" required>
        </div>
        <div class="field">
            <label for="contenu">Contenu</label>
            <textarea id="contenu" name="contenu" placeholder="Écrivez votre article…" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Publier</button>
    </form>
</div>
<?= $this->endSection() ?>
