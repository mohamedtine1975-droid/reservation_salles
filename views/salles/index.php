<?php $title = "Liste des salles"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Titre et description -->
<h2>📋 Salles disponibles</h2>
<p>Choisissez une salle pour effectuer une réservation.</p>

<!-- Afficher un message si aucune salle n'existe -->
<?php if (empty($salles)): ?>
    <div class="alert alert-info">
        Aucune salle disponible pour le moment.
    </div>
<?php else: ?>
    <!-- Grille de salles -->
    <div class="salles-grid">
        <!-- Boucler sur toutes les salles -->
        <?php foreach ($salles as $salle): ?>
            <!-- Carte d'une salle -->
            <div class="salle-card">
                <!-- Nom de la salle -->
                <h3><?= htmlspecialchars($salle['nom']) ?></h3>
                
                <!-- Informations principales -->
                <p class="salle-info">
                    <strong>Capacité :</strong> <?= htmlspecialchars($salle['capacite']) ?> personnes<br>
                    <strong>Localisation :</strong> <?= htmlspecialchars($salle['localisation']) ?>
                </p>
                
                <!-- Description si elle existe -->
                <?php if (!empty($salle['description'])): ?>
                    <p class="salle-description">
                        <?= htmlspecialchars($salle['description']) ?>
                    </p>
                <?php endif; ?>
                
                <!-- Boutons d'actions -->
                <div class="salle-actions">
                    <!-- Lien vers les détails -->
                    <a href="index.php?action=detail_salle&id=<?= $salle['id'] ?>" 
                       class="btn btn-secondary">Détails</a>
                    <!-- Lien vers la réservation -->
                    <a href="index.php?action=reserver&salle_id=<?= $salle['id'] ?>" 
                       class="btn btn-primary">Réserver</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
