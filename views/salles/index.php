<?php $title = "Liste des salles"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>📋 Salles disponibles</h2>

<p>Choisissez une salle pour effectuer une réservation.</p>

<?php if (empty($salles)): ?>
    <div class="alert alert-info">
        Aucune salle disponible pour le moment.
    </div>
<?php else: ?>
    <div class="salles-grid">
        <?php foreach ($salles as $salle): ?>
            <div class="salle-card">
                <h3><?= htmlspecialchars($salle['nom']) ?></h3>
                <p class="salle-info">
                    <strong>Capacité :</strong> <?= htmlspecialchars($salle['capacite']) ?> personnes<br>
                    <strong>Localisation :</strong> <?= htmlspecialchars($salle['localisation']) ?>
                </p>
                
                <?php if (!empty($salle['description'])): ?>
                    <p class="salle-description">
                        <?= htmlspecialchars($salle['description']) ?>
                    </p>
                <?php endif; ?>
                
                <div class="salle-actions">
                    <a href="index.php?action=detail_salle&id=<?= $salle['id'] ?>" 
                       class="btn btn-secondary">Détails</a>
                    <a href="index.php?action=reserver&salle_id=<?= $salle['id'] ?>" 
                       class="btn btn-primary">Réserver</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
