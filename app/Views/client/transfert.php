<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>MobiMoney - Transfert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 500px;">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <a href="<?= base_url('client/dashboard') ?>" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Retour</a>
            <h4 class="fw-bold mt-3 mb-4 text-info"><i class="bi bi-send me-2"></i>Transférer de l'argent</h4>
            
            <?php if (session()->getFlashdata('erreur')): ?>
                <div class="alert alert-danger py-2 small"><?= esc(session()->getFlashdata('erreur')) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('client/store-transfert') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small">Numéro du destinataire</label>
                    <input type="text" name="numero_destinataire" class="form-control" placeholder="Ex: 033xx xxx xx" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Montant à envoyer (Ar)</label>
                    <input type="number" id="montantInput" name="montant" class="form-control text-center fw-bold" placeholder="0.00" required>
                </div>

                <div class="bg-light p-3 rounded-3 mb-4 border text-secondary small">
                    <div class="d-flex justify-content-between mb-1"><span>Frais d'envoi :</span> <span class="fw-bold text-dark" id="txtFrais">0 Ar</span></div>
                    <div class="d-flex justify-content-between border-top pt-2"><span>Coût global de l'opération :</span> <span class="fw-bold text-info fs-6" id="txtTotal">0 Ar</span></div>
                </div>

                <button class="btn btn-info text-white btn-lg w-100 rounded-3">Envoyer maintenant</button>
            </form>
        </div>
    </div>
</div>

<script>
    const baremes = <?= $baremes ?>;
    const montantInput = document.getElementById('montantInput');
    const txtFrais = document.getElementById('txtFrais');
    const txtTotal = document.getElementById('txtTotal');

    montantInput.addEventListener('input', function() {
        const montant = parseFloat(this.value) || 0;
        let frais = 0;
        let trouve = false;

        if(montant > 0) {
            for (let b of baremes) {
                if (montant >= b.montant_min && montant <= b.montant_max) {
                    frais = parseFloat(b.frais);
                    trouve = true;
                    break;
                }
            }
        }
        txtFrais.innerText = trouve ? `${frais.toLocaleString('fr-FR')} Ar` : "Hors barème";
        txtTotal.innerText = trouve ? `${(montant + frais).toLocaleString('fr-FR')} Ar` : "0 Ar";
    });
</script>
</body>
</html>