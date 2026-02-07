<?php
/**
 * Point d'entrée de l'application
 * Routeur simple MVC
 */

// Démarrer la session
session_start();

// Inclure les contrôleurs
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/SalleController.php';
require_once __DIR__ . '/../controllers/ReservationController.php';

// Récupérer l'action demandée
$action = $_GET['action'] ?? 'login';

// Router les actions
switch ($action) {
    // Authentification
    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'login':
        $controller = new AuthController();
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

    // Salles
    case 'salles':
        $controller = new SalleController();
        $controller->index();
        break;

    case 'detail_salle':
        $controller = new SalleController();
        $controller->detail();
        break;

    // Réservations
    case 'reserver':
        $controller = new ReservationController();
        $controller->create();
        break;

    case 'store_reservation':
        $controller = new ReservationController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            header('Location: index.php?action=salles');
            exit;
        }
        break;

    case 'historique':
        $controller = new ReservationController();
        $controller->historique();
        break;

    // Page par défaut
    default:
        header('Location: index.php?action=login');
        exit;
}
