<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<!-- ── Carte solde ────────────────────────────────────────── -->
<div class="client-balance-card">
    <p class="client-balance-label">Solde disponible</p>
    <p class="client-balance-amount">
        <?= number_format($solde ?? 0, 0, ',', ' ') ?>
        <span class="client-balance-unit">Ar</span>
    </p>
    <p class="client-balance-phone">
        <i class="bi bi-phone" style="font-size:.75rem;margin-right:.25rem;"></i>
        <?= esc($telephone ?? '') ?>
    </p>
</div>

<!-- ── Actions rapides ────────────────────────────────────── -->
<p style="font-size:.72rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;
          color:#94a3b8;margin-bottom:.875rem;">Opérations</p>

<div class="client-actions-grid">

    <a href="<?= base_url('client/depot') ?>" class="client-action-tile">
        <span class="client-action-tile-icon" style="background:#ecfdf5;color:#10b981;">
            <i class="bi bi-arrow-down-left-circle"></i>
        </span>
        <div>
            <div class="client-action-tile-label">Dépôt</div>
            <div class="client-action-tile-sub">Alimenter le compte</div>
        </div>
    </a>

    <a href="<?= base_url('client/retrait') ?>" class="client-action-tile">
        <span class="client-action-tile-icon" style="background:#fef2f2;color:#ef4444;">
            <i class="bi bi-arrow-up-right-circle"></i>
        </span>
        <div>
            <div class="client-action-tile-label">Retrait</div>
            <div class="client-action-tile-sub">Retirer en agence</div>
        </div>
    </a>

    <a href="<?= base_url('client/transfert') ?>" class="client-action-tile">
        <span class="client-action-tile-icon" style="background:#eff6ff;color:#3b82f6;">
            <i class="bi bi-send"></i>
        </span>
        <div>
            <div class="client-action-tile-label">Transfert</div>
            <div class="client-action-tile-sub">Envoyer de l'argent</div>
        </div>
    </a>

    <a href="<?= base_url('client/historique') ?>" class="client-action-tile">
        <span class="client-action-tile-icon" style="background:#fffbeb;color:#f59e0b;">
            <i class="bi bi-clock-history"></i>
        </span>
        <div>
            <div class="client-action-tile-label">Historique</div>
            <div class="client-action-tile-sub">Voir les transactions</div>
        </div>
    </a>

</div>

<!-- ── Statut compte ──────────────────────────────────────── -->
<div style="display:flex;align-items:center;gap:.5rem;
            padding:.875rem 1rem;background:#fff;border:1px solid #e2e8f0;
            border-radius:.75rem;font-size:.825rem;">
    <span style="width:.6rem;height:.6rem;border-radius:50%;background:#10b981;flex-shrink:0;"></span>
    <span style="font-weight:500;color:#374151;">Compte actif</span>
    <span style="margin-left:auto;font-size:.75rem;color:#94a3b8;"><?= esc($telephone ?? '') ?></span>
</div>

<?= $this->endSection() ?>
