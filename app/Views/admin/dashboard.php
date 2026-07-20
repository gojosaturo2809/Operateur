<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Administration<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="page-title">Administration</h1>
<p class="page-sub">Gestion des utilisateurs.</p>

<div class="table-wrap">
<table class="data">
    <thead>
        <tr>
            <th>ID</th><th>Nom</th><th>Email</th>
            <th>Mot de passe</th><th>Rôle</th><th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= (int) $u['id'] ?></td>
            <td><?= esc($u['nom']) ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= esc($u['mot_de_passe']) ?></td>
            <td><span class="role-<?= esc($u['role']) ?>"><?= esc($u['role']) ?></span></td>
            <td><a class="link-danger" href="/admin/delete/<?= (int) $u['id'] ?>">Supprimer</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?= $this->endSection() ?>
