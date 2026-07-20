<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-4">
<div class="container" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-cash-coin me-2"></i>Barèmes des frais par tranche</h4>
        <a href="<?= base_url('operateur/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Menu Admin</a>
    </div>

    <!-- Messages Flash notifications -->
    <?php if(session()->getFlashdata('succes')): ?>
        <div class="alert alert-success py-2 small"><?= session()->getFlashdata('succes') ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout d'une tranche de barème -->
    <div class="card card-body shadow-sm border-0 mb-4">
        <form method="post" action="<?= base_url('operateur/baremes/store') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Type d'opération</label>
                <select name="id_type_operation" class="form-select" required>
                    <option value="">Choisir...</option>
                    <?php foreach($types as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= esc(ucfirst($t['nom'])) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Montant Min</label>
                <input type="number" step="0.01" name="montant_min" class="form-control" placeholder="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Montant Max</label>
                <input type="number" step="0.01" name="montant_max" class="form-control" placeholder="100000" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Frais appliqués</label>
                <input type="number" step="0.01" name="frais" class="form-control" placeholder="Frais fixe" required>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter la règle tarifaire</button>
            </div>
        </form>
    </div>

    <!-- Table des données -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Type</th>
                    <th>Tranche Min</th>
                    <th>Tranche Max</th>
                    <th>Frais</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($baremes as $b): ?>
                <tr>
                    <td class="fw-bold text-uppercase text-secondary small"><?= esc($b['type_nom']) ?></td>
                    <td><?= number_format($b['montant_min'], 2, ',', ' ') ?> Ar</td>
                    <td><?= number_format($b['montant_max'], 2, ',', ' ') ?> Ar</td>
                    <td class="fw-bold text-danger"><?= number_format($b['frais'], 2, ',', ' ') ?> Ar</td>
                    <td class="text-end">
                        <a href="<?= base_url('operateur/baremes/delete/'.$b['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette règle de frais ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($baremes)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Aucun barème tarifaire enregistré pour le moment.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>