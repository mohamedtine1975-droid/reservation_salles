<?php $title = "Inscription"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Conteneur d'authentification -->
<div class="auth-container">
    <div class="auth-box">
        <!-- Titre de la page -->
        <h2>Créer un compte</h2>

        <!-- Afficher les messages d'erreur s'il y en a -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form method="POST" action="index.php?action=register">
            <!-- Champ Nom -->
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" 
                       value="<?= htmlspecialchars($old_data['nom'] ?? '') ?>" >
            </div>

            <!-- Champ Prénom -->
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" 
                       value="<?= htmlspecialchars($old_data['prenom'] ?? '') ?>" >
            </div>

            <!-- Champ Email -->
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($old_data['email'] ?? '') ?>" >
            </div>

            <!-- Champ Mot de passe (min 6 caractères) -->
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe * (min. 6 caractères)</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" >
            </div>

            <!-- Champ Confirmation mot de passe -->
            <div class="form-group">
                <label for="confirmer_mot_de_passe">Confirmer le mot de passe *</label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" >
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>

        <!-- Lien vers la connexion -->
        <p class="auth-link">
            Vous avez déjà un compte ?
            <a href="index.php?action=login">Se connecter</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
