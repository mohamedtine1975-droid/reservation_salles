<?php $title = htmlspecialchars($this->salle->nom); ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Détails de la salle -->
<div class="salle-detail">
    <!-- Titre : nom de la salle -->
    <h2><?= htmlspecialchars($this->salle->nom) ?></h2>

    <!-- Boîte avec les informations détaillées -->
    <div class="detail-box">
        <!-- Capacité -->
        <div class="detail-item">
            <strong>Capacité :</strong> 
            <?= htmlspecialchars($this->salle->capacite) ?> personnes
        </div>

        <!-- Localisation -->
        <div class="detail-item">
            <strong>Localisation :</strong> 
            <?= htmlspecialchars($this->salle->localisation) ?>
        </div>

        <!-- Description (si elle existe) -->
        <?php if (!empty($this->salle->description)): ?>
            <div class="detail-item">
                <strong>Description :</strong><br>
                <!-- Convertir les retours à la ligne en balises <br> -->
                <?= nl2br(htmlspecialchars($this->salle->description)) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Boutons d'actions -->
    <div class="actions-buttons">
        <!-- Retour à la liste des salles -->
        <a href="index.php?action=salles" class="btn btn-secondary">Retour à la liste</a>
        <!-- Accéder au formulaire de réservation -->
        <a href="index.php?action=reserver&salle_id=<?= $this->salle->id ?>" 
           class="btn btn-primary">Réserver cette salle</a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
