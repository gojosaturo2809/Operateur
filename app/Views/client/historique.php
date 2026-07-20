<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>MobiMoney - Historique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-4">
<div class="container" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Mes Transactions</h4>
        <a href="<?= base_url('client/dashboard') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-house"></i> Dashboard</a>
    </div>

    <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date & Heure</th>
                        <th>Type d'opération</th>
                        <th>Détails / Tiers</th>
                        <th class="text-end">Montant brut</th>
                        <th class="text-end">Frais appliqués</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Aucune opération enregistrée pour le moment.</td></tr>
                    <?php else: ?>
                        <?php foreach ($historique as $op): 
                            // Analyse fine du sens de l'opération
                            $estAuteur = ($op['auteur_telephone'] === $telephone);
                            $estTransfert = ($op['type_nom'] === 'transfert');
                            
                            if ($op['type_nom'] === 'depot') {
                                $classCouleur = 'table-success text-success fw-bold';
                                $prefixeFlux = '+ ';
                                $details = 'Versement en Agence';
                            } elseif ($op['type_nom'] === 'retrait') {
                                $classCouleur = 'table-danger text-danger fw-bold';
                                $prefixeFlux = '- ';
                                $details = 'Retrait Guichet';
                            } else { // Cas du transfert
                                if ($estAuteur) {
                                    $classCouleur = 'table-danger text-danger fw-bold';
                                    $prefixeFlux = '- ';
                                    $details = 'Vers le : ' . esc($op['numero_destinataire']);
                                } else {
                                    $classCouleur = 'table-success text-success fw-bold';
                                    $prefixeFlux = '+ ';
                                    $details = 'Reçu de : ' . esc($op['auteur_telephone']);
                                }
                            }
                        ?>
                            <tr>
                                <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($op['date_operation'])) ?></td>
                                <td>
                                    <span class="badge rounded-pill bg-secondary text-uppercase small"><?= esc($op['type_nom']) ?></span>
                                </td>
                                <td class="small fw-semibold"><?= $details ?></td>
                                <td class="text-end <?= $classCouleur ?>">
                                    <?= $prefixeFlux . number_format($op['montant'], 2, ',', ' ') ?> Ar
                                </td>
                                <td class="text-end text-muted small">
                                    <?= ($estAuteur && $op['frais_applique'] > 0) ? number_format($op['frais_applique'], 2, ',', ' ') . ' Ar' : '--' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>