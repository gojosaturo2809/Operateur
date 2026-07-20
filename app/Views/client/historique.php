<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <table class="table mb-0 align-middle">
        <thead><tr><th>Date</th><th>Type</th><th>Destinataire</th><th>Montant</th><th>Frais</th></tr></thead>
        <tbody>
        <?php foreach (($operations ?? []) as $o): ?>
            <tr>
                <td class="small text-muted"><?= esc($o['date_operation']) ?></td>
                <td>
                    <?php
                        $badge = ['depot' => 'success', 'retrait' => 'warning', 'transfert' => 'info'][$o['nom_type']] ?? 'secondary';
                    ?>
                    <span class="badge text-bg-<?= $badge ?>"><?= esc($o['nom_type']) ?></span>
                </td>
                <td><?= esc($o['numero_destinataire'] ?? '-') ?></td>
                <td><?= number_format($o['montant'],0,',',' ') ?> Ar</td>
                <td><?= number_format($o['frais_applique'],0,',',' ') ?> Ar</td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($operations)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Aucune opération</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
