<?php $title = htmlspecialchars($this->salle->nom); ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="salle-detail">
    <h2><?= htmlspecialchars($this->salle->nom) ?></h2>

    <div class="detail-box">
        <div class="detail-item">
            <strong>Capacité :</strong> 
            <?= htmlspecialchars($this->salle->capacite) ?> personnes
        </div>

        <div class="detail-item">
            <strong>Localisation :</strong> 
            <?= htmlspecialchars($this->salle->localisation) ?>
        </div>

        <?php if (!empty($this->salle->description)): ?>
            <div class="detail-item">
                <strong>Description :</strong><br>
                <?= nl2br(htmlspecialchars($this->salle->description)) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="actions-buttons">
        <a href="index.php?action=salles" class="btn btn-secondary">Retour à la liste</a>
        <a href="index.php?action=reserver&salle_id=<?= $this->salle->id ?>" 
           class="btn btn-primary">Réserver cette salle</a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
