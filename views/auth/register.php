<?php $title = "Inscription"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Créer un compte</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" 
                       value="<?= htmlspecialchars($old_data['nom'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" 
                       value="<?= htmlspecialchars($old_data['prenom'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($old_data['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe * (min. 6 caractères)</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <div class="form-group">
                <label for="confirmer_mot_de_passe">Confirmer le mot de passe *</label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
            </div>

            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>

        <p class="auth-link">
            Vous avez déjà un compte ?
            <a href="index.php?action=login">Se connecter</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
