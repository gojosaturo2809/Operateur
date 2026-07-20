<?= $this->extend('layouts/client') ?>
<?= $this->section('content') ?>
<a href="<?= base_url('client/dashboard') ?>" class="client-back"><i class="bi bi-arrow-left"></i> Retour</a>
<div class="client-form-card"><div class="client-form-header"><span class="client-form-icon" style="background:#f3e8ff;color:#9333ea;"><i class="bi bi-people-fill"></i></span><div><p class="client-form-title">Envoi multiple</p><p class="client-form-sub">Répartir le montant entre plusieurs numéros autorisés</p></div></div><div class="client-form-body">
<form method="post" action="<?= base_url('client/storeEnvoiMultiple') ?>"><?= csrf_field() ?>
<label class="client-field-label" for="montantGlobal">Montant global</label><div class="client-amount-wrap"><input id="montantGlobal" type="number" name="montant_global" min="1" class="client-amount-input" required><span class="client-amount-unit">Ar</span></div>
<label class="client-field-label" for="numerosBruts">Numéros destinataires</label><textarea id="numerosBruts" name="numeros_bruts" class="client-input" style="height:8rem;padding:.75rem" placeholder="Séparer les numéros par virgule, espace ou retour à la ligne" required></textarea>
<div class="client-recap"><div class="client-recap-row"><span>Numéros détectés</span><span id="nombre">0</span></div><div class="client-recap-row client-recap-total"><span>Montant par personne</span><span id="part">—</span></div></div>
<button class="client-btn" type="submit" style="background:#9333ea"><i class="bi bi-share-fill"></i> Envoyer</button></form></div></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?><script>const m=document.getElementById('montantGlobal'),n=document.getElementById('numerosBruts'),c=document.getElementById('nombre'),p=document.getElementById('part');function u(){const a=n.value.trim().split(/[\s,;]+/).filter(Boolean),v=Number(m.value)||0;c.textContent=a.length;p.textContent=a.length&&v?(v/a.length).toLocaleString('fr-FR')+' Ar':'—'}m.oninput=n.oninput=u;u();</script><?= $this->endSection() ?>
