<?php $title = "Mes réservations"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>📖 Historique de mes réservations</h2>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if (empty($reservations)): ?>
    <div class="alert alert-info">
        Vous n'avez aucune réservation pour le moment.
    </div>
    <p>
        <a href="index.php?action=salles" class="btn btn-primary">
            Consulter les salles disponibles
        </a>
    </p>
<?php else: ?>
    <div class="reservations-list">
        <?php foreach ($reservations as $reservation): ?>
            <?php
                $date_obj = new DateTime($reservation['date_reservation']);
                $date_formatted = $date_obj->format('d/m/Y');
                
                // Déterminer la classe CSS selon le statut
                $statut_class = '';
                $statut_text = '';
                switch ($reservation['statut']) {
                    case 'confirmee':
                        $statut_class = 'statut-confirmee';
                        $statut_text = 'Confirmée';
                        break;
                    case 'en_attente':
                        $statut_class = 'statut-attente';
                        $statut_text = 'En attente';
                        break;
                    case 'annulee':
                        $statut_class = 'statut-annulee';
                        $statut_text = 'Annulée';
                        break;
                }
            ?>
            <div class="reservation-card">
                <div class="reservation-header">
                    <h3><?= htmlspecialchars($reservation['salle_nom']) ?></h3>
                    <span class="statut-badge <?= $statut_class ?>">
                        <?= $statut_text ?>
                    </span>
                </div>
                
                <div class="reservation-details">
                    <p>
                        <strong>📅 Date :</strong> <?= $date_formatted ?>
                    </p>
                    <p>
                        <strong>🕐 Horaire :</strong> 
                        <?= htmlspecialchars(substr($reservation['heure_debut'], 0, 5)) ?> 
                        - 
                        <?= htmlspecialchars(substr($reservation['heure_fin'], 0, 5)) ?>
                    </p>
                    <p>
                        <strong>📍 Localisation :</strong> 
                        <?= htmlspecialchars($reservation['localisation']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
