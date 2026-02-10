<?php
// Contrôleur pour gérer les réservations

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Salle.php';

class ReservationController {
    private $db;
    private $reservation;
    private $salle;

    // Initialiser les dépendances
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->reservation = new Reservation($this->db);
        $this->salle = new Salle($this->db);
    }

    // Vérifier que l'utilisateur est authentifié
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    // Afficher le formulaire de création de réservation
    public function create() {
        $this->requireLogin();

        // Vérifier et récupérer l'ID de la salle
        if (empty($_GET['salle_id']) || !is_numeric($_GET['salle_id'])) {
            header('Location: index.php?action=salles');
            exit;
        }

        $salle_id = (int)$_GET['salle_id'];
        
        // Vérifier que la salle existe
        if (!$this->salle->getById($salle_id)) {
            $_SESSION['errors'] = ["Salle introuvable."];
            header('Location: index.php?action=salles');
            exit;
        }

        // Récupérer les messages de session et les nettoyer
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];
        unset($_SESSION['errors'], $_SESSION['old_data']);

        require_once __DIR__ . '/../views/reservations/create.php';
    }

    // Traiter l'enregistrement d'une nouvelle réservation
    public function store() {
        $this->requireLogin();

        // Récupérer les données du formulaire
        $salle_id = $_POST['salle_id'] ?? null;
        $date = $_POST['date_reservation'] ?? null;
        $heure_debut = $_POST['heure_debut'] ?? null;
        $heure_fin = $_POST['heure_fin'] ?? null;

        $errors = [];

        // Valider la salle
        if (empty($salle_id) || !is_numeric($salle_id) || !$this->salle->exists($salle_id)) {
            $errors[] = "La salle est invalide.";
        }

        // Valider la date de réservation
        if (empty($date)) {
            $errors[] = "La date de réservation est obligatoire.";
        } elseif (!$this->isValidDate($date) || new DateTime($date) < new DateTime('today')) {
            $errors[] = "La date doit être valide et dans le futur.";
        }

        // Valider les heures
        if (empty($heure_debut)) {
            $errors[] = "L'heure de début est obligatoire.";
        }
        if (empty($heure_fin)) {
            $errors[] = "L'heure de fin est obligatoire.";
        }
        if (!empty($heure_debut) && !empty($heure_fin) && $heure_fin <= $heure_debut) {
            $errors[] = "L'heure de fin doit être après l'heure de début.";
        }

        // Vérifier que la salle est disponible au créneau demandé
        if (empty($errors) && !$this->reservation->isAvailable($salle_id, $date, $heure_debut, $heure_fin)) {
            $errors[] = "Cette salle est déjà réservée pour ce créneau.";
        }

        // S'il y a des erreurs, retourner au formulaire
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=reserver&salle_id=' . $salle_id);
            exit;
        }

        // Créer et enregistrer la réservation
        $this->reservation->user_id = $_SESSION['user_id'];
        $this->reservation->salle_id = $salle_id;
        $this->reservation->date_reservation = $date;
        $this->reservation->heure_debut = $heure_debut;
        $this->reservation->heure_fin = $heure_fin;
        $this->reservation->statut = 'confirmee';

        // Vérifier si l'enregistrement a réussi
        if ($this->reservation->create()) {
            $_SESSION['success'] = "Réservation effectuée avec succès !";
            header('Location: index.php?action=historique');
        } else {
            $_SESSION['errors'] = ["Une erreur est survenue."];
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=reserver&salle_id=' . $salle_id);
        }
        exit;
    }

    // Valider le format de la date (YYYY-MM-DD)
    private function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // Afficher l'historique des réservations de l'utilisateur
    public function historique() { 
        $this->requireLogin();

        // Récupérer toutes les réservations de l'utilisateur
        $reservations = $this->reservation->getByUserId($_SESSION['user_id'])->fetchAll();
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);

        require_once __DIR__ . '/../views/reservations/historique.php';
    }
}
