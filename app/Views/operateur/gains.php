<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Situation des gains de l'opérateur</h5>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Type d'opération</th>
                    <th class="text-center">Volume transactions</th>
                    <th class="text-end">Volume financier</th>
                    <th class="text-end">Total gains</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach (($situationGains ?? []) as $g): ?>
                <tr>
                    <td>
                        <span class="badge text-bg-secondary">
                            <?= esc($g['type_operation']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?= $g['volume_transactions'] ?>
                    </td>
                    <td class="text-end">
                        <?= number_format($g['volume_financier'], 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-end fw-bold text-success">
                        <?= number_format($g['total_gains'], 0, ',', ' ') ?> Ar
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">
                        Gain total de l'opérateur
                    </th>
                    <th class="text-end text-success">
                        <?= number_format($gainTotal ?? 0, 0, ',', ' ') ?> Ar
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= $this->endSection() ?>