<!-- En-tête HTML - Titre, CSS et navigation -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définir l'encodage et les paramètres -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre dynamique de la page -->
    <title><?= $title ?? 'Système de Réservation de Salles' ?></title>
    <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- Barre d'en-tête avec navigation -->
    <header>
        <div class="container">
            <!-- Titre principal -->
            <h1>🏢 Réservation de Salles</h1>
            
            <!-- Menu de navigation si l'utilisateur est connecté -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav>
                    <!-- Lien vers la liste des salles -->
                    <a href="index.php?action=salles">Salles</a>
                    <!-- Lien vers l'historique des réservations -->
                    <a href="index.php?action=historique">Mes réservations</a>
                    <!-- Afficher le nom complet de l'utilisateur -->
                    <span class="user-info">
                        👤 <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?> 
                    </span>
                    <!-- Bouton de déconnexion -->
                    <a href="index.php?action=logout" class="btn-logout">Déconnexion</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container">
