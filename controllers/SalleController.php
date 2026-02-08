<?php
// Contrôleur pour les salles
// Gère l'affichage de la liste et des détails des salles

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Salle.php';

class SalleController {
    private $db;     // Connexion à la base
    private $salle;  // Objet salle

    // Initialiser les dépendances
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->salle = new Salle($this->db);
    }

    // Afficher la liste de toutes les salles
    public function index() {
        // Vérifier que l'utilisateur est authentifié
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Récupérer toutes les salles et les convertir en array
        $stmt = $this->salle->getAll();
        $salles = $stmt->fetchAll();

        require_once __DIR__ . '/../views/salles/index.php';
    }

    // Afficher les détails d'une salle
    public function detail() {
        // Vérifier que l'utilisateur est authentifié
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Vérifier que l'ID de la salle est fourni et valide
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?action=salles');
            exit;
        }

        $id = (int)$_GET['id'];
        
        // Charger la salle et afficher ses détails
        if ($this->salle->getById($id)) {
            require_once __DIR__ . '/../views/salles/detail.php';
        } else {
            // Salle non trouvée
            $_SESSION['errors'] = ["Salle introuvable."];
            header('Location: index.php?action=salles');
            exit;
        }
    }
}
