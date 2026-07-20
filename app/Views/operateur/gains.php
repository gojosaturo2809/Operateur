<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead><tr><th>Type d'opération</th><th>Volume transactions</th><th>Volume financier</th><th>Total gains</th></tr></thead>
        <tbody>
        <?php foreach (($situationGains ?? []) as $g): ?>
            <tr>
                <td><span class="badge text-bg-secondary"><?= esc($g['type_operation']) ?></span></td>
                <td><?= $g['volume_transactions'] ?></td>
                <td><?= number_format($g['volume_financier'],0,',',' ') ?> Ar</td>
                <td class="fw-bold"><?= number_format($g['total_gains'],0,',',' ') ?> Ar</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
