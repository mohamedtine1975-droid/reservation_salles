<?php $title = "Réserver " . htmlspecialchars($this->salle->nom); ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2>📅 Réserver : <?= htmlspecialchars($this->salle->nom) ?></h2>

<div class="reservation-info">
    <p><strong>Capacité :</strong> <?= htmlspecialchars($this->salle->capacite) ?> personnes</p>
    <p><strong>Localisation :</strong> <?= htmlspecialchars($this->salle->localisation) ?></p>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=store_reservation" class="reservation-form">
    <input type="hidden" name="salle_id" value="<?= $this->salle->id ?>">

    <div class="form-group">
        <label for="date_reservation">Date de réservation *</label>
        <input type="date" id="date_reservation" name="date_reservation" 
               value="<?= htmlspecialchars($old_data['date_reservation'] ?? '') ?>" 
               min="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="heure_debut">Heure de début *</label>
            <input type="time" id="heure_debut" name="heure_debut" 
                   value="<?= htmlspecialchars($old_data['heure_debut'] ?? '08:00') ?>" required>
        </div>

        <div class="form-group">
            <label for="heure_fin">Heure de fin *</label>
            <input type="time" id="heure_fin" name="heure_fin" 
                   value="<?= htmlspecialchars($old_data['heure_fin'] ?? '10:00') ?>" required>
        </div>
    </div>

    <div class="form-actions">
        <a href="index.php?action=salles" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
    </div>
</form>

<?php include __DIR__ . '/../layout/footer.php'; ?>
