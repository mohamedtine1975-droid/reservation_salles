<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Système de Réservation de Salles' ?></title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🏢 Réservation de Salles</h1>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav>
                    <a href="index.php?action=salles">Salles</a>
                    <a href="index.php?action=historique">Mes réservations</a>
                    <span class="user-info">
                        👤 <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?>
                    </span>
                    <a href="index.php?action=logout" class="btn-logout">Déconnexion</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
