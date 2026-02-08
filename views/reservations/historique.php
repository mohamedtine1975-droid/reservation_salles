<?php $title = "Mes réservations"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Titre de la page -->
<h2>📖 Historique de mes réservations</h2>

<!-- Afficher le message de succès si une réservation vient d'être créée -->
<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<!-- Afficher un message si l'utilisateur n'a aucune réservation -->
<?php if (empty($reservations)): ?>
    <div class="alert alert-info">
        Vous n'avez aucune réservation pour le moment.
    </div>
    <!-- Lien pour consulter les salles -->
    <p>
        <a href="index.php?action=salles" class="btn btn-primary">
            Consulter les salles disponibles
        </a>
    </p>
<?php else: ?>
    <!-- Afficher la liste des réservations -->
    <div class="reservations-list">
        <!-- Boucler sur toutes les réservations -->
        <?php foreach ($reservations as $reservation): ?>
            <?php
                // Formater la date
                $date_obj = new DateTime($reservation['date_reservation']);
                $date_formatted = $date_obj->format('d/m/Y');
                
                // Déterminer la classe CSS et le texte selon le statut
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
            <!-- Carte de réservation -->
            <div class="reservation-card">
                <!-- En-tête avec nom de salle et statut -->
                <div class="reservation-header">
                    <h3><?= htmlspecialchars($reservation['salle_nom']) ?></h3>
                    <!-- Badge du statut -->
                    <span class="statut-badge <?= $statut_class ?>">
                        <?= $statut_text ?>
                    </span>
                </div>
                
                <!-- Détails de la réservation -->
                <div class="reservation-details">
                    <!-- Date -->
                    <p>
                        <strong>📅 Date :</strong> <?= $date_formatted ?>
                    </p>
                    <!-- Horaire -->
                    <p>
                        <strong>🕐 Horaire :</strong> 
                        <?= htmlspecialchars(substr($reservation['heure_debut'], 0, 5)) ?> 
                        - 
                        <?= htmlspecialchars(substr($reservation['heure_fin'], 0, 5)) ?>
                    </p>
                    <!-- Localisation -->
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
