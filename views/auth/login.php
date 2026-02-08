<?php $title = "Connexion"; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Conteneur d'authentification -->
<div class="auth-container">
    <div class="auth-box">
        <!-- Titre de la page -->
        <h2>Connexion</h2>

        <!-- Afficher le message de succès si l'inscription est réussie -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

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

        <!-- Formulaire de connexion -->
        <form method="POST" action="index.php?action=login">
            <!-- Champ Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <!-- Champ Mot de passe -->
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <!-- Lien vers l'inscription -->
        <p class="auth-link">
            Vous n'avez pas de compte ? 
            <a href="index.php?action=register">S'inscrire</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
