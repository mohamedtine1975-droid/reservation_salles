<?php
/**
 * Contrôleur des salles
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Salle.php';

class SalleController {
    private $db;
    private $salle;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->salle = new Salle($this->db);
    }

    /**
     * Afficher la liste des salles
     */
    public function index() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $stmt = $this->salle->getAll();
        $salles = $stmt->fetchAll();

        require_once __DIR__ . '/../views/salles/index.php';
    }

    /**
     * Afficher les détails d'une salle
     */
    public function detail() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Vérifier que l'ID est fourni
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?action=salles');
            exit;
        }

        $id = (int)$_GET['id'];
        
        if ($this->salle->getById($id)) {
            require_once __DIR__ . '/../views/salles/detail.php';
        } else {
            $_SESSION['errors'] = ["Salle introuvable."];
            header('Location: index.php?action=salles');
            exit;
        }
    }
}
