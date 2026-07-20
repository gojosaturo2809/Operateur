<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Numéro</th><th>Solde</th></tr></thead>
        <tbody>
        <?php foreach (($clients ?? []) as $c): ?>
            <tr>
                <td><?= $c['client_id'] ?></td>
                <td><?= esc($c['numero_telephone']) ?></td>
                <td><?= number_format($c['solde'] ?? 0,0,',',' ') ?> Ar</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
