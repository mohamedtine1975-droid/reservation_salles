<?php
// Point d'entrée principal de l'application
// Routeur MVC - Dirige les requêtes vers les bons contrôleurs

// Démarrer la session pour stocker les infos utilisateur
session_start();

// Inclure les contrôleurs
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/SalleController.php';
require_once __DIR__ . '/../controllers/ReservationController.php';

// Récupérer l'action demandée depuis l'URL (GET), par défaut 'login'
$action = $_GET['action'] ?? 'login';

// Router les actions vers les contrôleurs appropriés
switch ($action) {
    // === AUTHENTIFICATION ===
    case 'register':
        $controller = new AuthController();
        // Afficher le formulaire en GET, traiter l'inscription en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'login':
        $controller = new AuthController();
        // Afficher le formulaire en GET, traiter la connexion en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // === SALLES ===
    case 'salles':
        $controller = new SalleController();
        $controller->index();  // Afficher la liste des salles
        break;

    case 'detail_salle':
        $controller = new SalleController();
        $controller->detail();  // Afficher les détails d'une salle
        break;

    // === RÉSERVATIONS ===
    case 'reserver':
        $controller = new ReservationController();
        $controller->create();  // Afficher le formulaire de réservation
        break;

    case 'store_reservation':
        $controller = new ReservationController();
        // Traiter l'enregistrement d'une réservation en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            header('Location: index.php?action=salles');
            exit;
        }
        break;

    case 'historique':
        $controller = new ReservationController();
        $controller->historique();  // Afficher l'historique des réservations
        break;

    // === PAGE PAR DÉFAUT ===
    default:
        header('Location: index.php?action=login');
        exit;
}
