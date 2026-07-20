<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Articles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="page-title">Articles</h1>
<p class="page-sub">Les derniers articles publiés.</p>

<?php if (empty($articles)): ?>
    <div class="card">Aucun article pour le moment. <a href="/articles/create">Écrire le premier</a>.</div>
<?php else: ?>
    <?php foreach ($articles as $a): ?>
        <article class="article">
            <h3><a href="#"><?= esc($a['titre']) ?></a></h3>
            <div class="meta">
                <span class="pill"><?= esc($a['auteur']) ?></span>
                <?php if (session()->get('role') === 'admin'): ?>
                    <a class="link-danger" href="/articles/delete/<?= (int) $a['id'] ?>">Supprimer</a>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>
