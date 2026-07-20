<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="client-page-header">
    <h2>Historique</h2>
    <p>Toutes vos transactions</p>
</div>

<?php if (empty($historique)): ?>
    <div style="text-align:center;padding:3rem 1rem;color:#94a3b8;">
        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.5;"></i>
        <p style="font-size:.9rem;font-weight:500;margin:0;">Aucune transaction pour le moment.</p>
    </div>

<?php else: ?>

    <div class="client-tx-list">
    <?php foreach ($historique as $op):
        $estAuteur = ($op['auteur_telephone'] ?? '') === ($telephone ?? '');
        $typeNom   = $op['type_nom'] ?? '';

        if ($typeNom === 'depot') {
            $icon     = 'bi-arrow-down-left-circle-fill';
            $iconBg   = '#ecfdf5';
            $iconClr  = '#10b981';
            $amtClr   = '#10b981';
            $prefixe  = '+';
            $detail   = 'Versement en agence';
        } elseif ($typeNom === 'retrait') {
            $icon     = 'bi-arrow-up-right-circle-fill';
            $iconBg   = '#fef2f2';
            $iconClr  = '#ef4444';
            $amtClr   = '#ef4444';
            $prefixe  = '−';
            $detail   = 'Retrait guichet';
        } else {
            if ($estAuteur) {
                $icon    = 'bi-send-fill';
                $iconBg  = '#eff6ff';
                $iconClr = '#3b82f6';
                $amtClr  = '#ef4444';
                $prefixe = '−';
                $detail  = 'Vers ' . esc($op['numero_destinataire'] ?? '');
            } else {
                $icon    = 'bi-send-fill';
                $iconBg  = '#ecfdf5';
                $iconClr = '#10b981';
                $amtClr  = '#10b981';
                $prefixe = '+';
                $detail  = 'Reçu de ' . esc($op['auteur_telephone'] ?? '');
            }
        }
    ?>
    <div class="client-tx-item">

        <!-- Icône -->
        <span class="client-tx-dot" style="background:<?= $iconBg ?>;color:<?= $iconClr ?>;">
            <i class="bi <?= $icon ?>"></i>
        </span>

        <!-- Infos -->
        <div class="client-tx-info">
            <div class="client-tx-type"><?= ucfirst($typeNom) ?></div>
            <div class="client-tx-detail"><?= $detail ?></div>
            <div class="client-tx-detail" style="margin-top:.15rem;">
                <?= date('d/m/Y · H:i', strtotime($op['date_operation'])) ?>
            </div>
        </div>

        <!-- Montant -->
        <div>
            <div class="client-tx-amount" style="color:<?= $amtClr ?>;">
                <?= $prefixe ?>&nbsp;<?= number_format($op['montant'], 0, ',', ' ') ?>&nbsp;Ar
            </div>
            <?php if ($estAuteur && ($op['frais_applique'] ?? 0) > 0): ?>
            <div class="client-tx-frais">
                Frais : <?= number_format($op['frais_applique'], 0, ',', ' ') ?> Ar
            </div>
            <?php endif; ?>
        </div>

    </div>
    <?php endforeach; ?>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>
