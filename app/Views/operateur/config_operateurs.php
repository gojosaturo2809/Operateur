<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-4">
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2"></i>Configuration Inter-Opérateurs <span class="badge bg-danger small">v2</span></h3>
        <a href="<?= base_url('operateur/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Menu Admin</a>
    </div>

    <!-- Notifications -->
    <?php if(session()->getFlashdata('succes')): ?>
        <div class="alert alert-success py-2 small"><?= session()->getFlashdata('succes') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('erreur')): ?>
        <div class="alert alert-danger py-2 small"><?= session()->getFlashdata('erreur') ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- COLONNE GAUCHE : GESTION DES OPERATEURS -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-building me-2"></i> Enregistrer un autre Opérateur
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('operateur/operateurs/store') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nom de l'opérateur</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Telma, Orange, Airtel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Commission additionnelle (%) pour transfert</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="commission_inter_pct" class="form-control" placeholder="Ex: 2.5" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-muted small">Pourcentage prélevé en plus lors d'un transfert vers ce réseau.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-circle me-1"></i> Ajouter l'opérateur</button>
                    </form>
                </div>
            </div>

            <!-- Liste des opérateurs configurés -->
            <div class="card shadow-sm border-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>Réseau</th>
                            <th>Type</th>
                            <th>Commission Inter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($operateurs as $op): ?>
                        <tr>
                            <td class="fw-bold"><?= esc($op['nom']) ?></td>
                            <td>
                                <?php if($op['est_principal']): ?>
                                    <span class="badge bg-success">Principal (Mon Réseau)</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tiers</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-danger"><?= number_format($op['commission_inter_pct'], 2) ?> %</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- COLONNE DROITE : ATTRIBUTION DES PREFIXES -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-phone me-2"></i> Assigner un Préfixe à un Réseau
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('operateur/prefixes/store') ?>">
                        <?= csrf_field() ?>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Préfixe</label>
                                <input type="text" name="prefixe" class="form-control" placeholder="Ex: 034" maxlength="5" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Attribuer au réseau</label>
                                <select name="id_operateur" class="form-select" required>
                                    <option value="">Choisir...</option>
                                    <?php foreach($operateurs as $op): ?>
                                        <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-link-45deg"></i> Lier le préfixe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table des préfixes et correspondances -->
            <div class="card shadow-sm border-0 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>Préfixe</th>
                            <th>Opérateur Associé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prefixes as $p): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= esc($p['prefixe']) ?></td>
                            <td>
                                <span class="fw-semibold"><?= esc($p['operateur_nom']) ?></span>
                                <?php if($p['est_principal']): ?>
                                    <i class="bi bi-check-circle-fill text-success ms-1" title="Réseau interne"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($prefixes)): ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">Aucun préfixe mappé.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>