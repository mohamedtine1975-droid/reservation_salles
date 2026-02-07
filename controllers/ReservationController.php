<?php
/**
 * Contrôleur des réservations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Salle.php';

class ReservationController {
    private $db;
    private $reservation;
    private $salle;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->reservation = new Reservation($this->db);
        $this->salle = new Salle($this->db);
    }

    /**
     * Afficher le formulaire de réservation
     */
    public function create() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Vérifier que l'ID de la salle est fourni
        if (!isset($_GET['salle_id']) || !is_numeric($_GET['salle_id'])) {
            header('Location: index.php?action=salles');
            exit;
        }

        $salle_id = (int)$_GET['salle_id'];
        
        if (!$this->salle->getById($salle_id)) {
            $_SESSION['errors'] = ["Salle introuvable."];
            header('Location: index.php?action=salles');
            exit;
        }

        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];
        unset($_SESSION['errors'], $_SESSION['old_data']);

        require_once __DIR__ . '/../views/reservations/create.php';
    }

    /**
     * Traiter la création d'une réservation
     */
    public function store() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $errors = [];
        $salle_id = (int)$_POST['salle_id'];

        // Validation des champs
        if (empty($_POST['salle_id']) || !is_numeric($_POST['salle_id'])) {
            $errors[] = "La salle est invalide.";
        } else {
            // Vérifier que la salle existe
            if (!$this->salle->exists($salle_id)) {
                $errors[] = "La salle n'existe pas.";
            }
        }

        if (empty($_POST['date_reservation'])) {
            $errors[] = "La date de réservation est obligatoire.";
        } else {
            // Vérifier que la date est valide et future
            $date = $_POST['date_reservation'];
            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
            
            if (!$date_obj || $date_obj->format('Y-m-d') !== $date) {
                $errors[] = "La date de réservation n'est pas valide.";
            } elseif ($date_obj < new DateTime('today')) {
                $errors[] = "La date de réservation doit être dans le futur.";
            }
        }

        if (empty($_POST['heure_debut'])) {
            $errors[] = "L'heure de début est obligatoire.";
        }

        if (empty($_POST['heure_fin'])) {
            $errors[] = "L'heure de fin est obligatoire.";
        }

        // Vérifier que l'heure de fin est après l'heure de début
        if (!empty($_POST['heure_debut']) && !empty($_POST['heure_fin'])) {
            $heure_debut = $_POST['heure_debut'];
            $heure_fin = $_POST['heure_fin'];
            
            if ($heure_fin <= $heure_debut) {
                $errors[] = "L'heure de fin doit être postérieure à l'heure de début.";
            }
        }

        // Vérifier la disponibilité de la salle
        if (empty($errors)) {
            if (!$this->reservation->isAvailable($salle_id, $_POST['date_reservation'], $_POST['heure_debut'], $_POST['heure_fin'])) {
                $errors[] = "Cette salle est déjà réservée pour ce créneau horaire.";
            }
        }

        // S'il y a des erreurs, retourner au formulaire
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=reserver&salle_id=' . $salle_id);
            exit;
        }

        // Créer la réservation
        $this->reservation->user_id = $_SESSION['user_id'];
        $this->reservation->salle_id = $salle_id;
        $this->reservation->date_reservation = $_POST['date_reservation'];
        $this->reservation->heure_debut = $_POST['heure_debut'];
        $this->reservation->heure_fin = $_POST['heure_fin'];
        $this->reservation->statut = 'confirmee';

        if ($this->reservation->create()) {
            $_SESSION['success'] = "Réservation effectuée avec succès !";
            header('Location: index.php?action=historique');
            exit;
        } else {
            $_SESSION['errors'] = ["Une erreur est survenue lors de la réservation."];
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=reserver&salle_id=' . $salle_id);
            exit;
        }
    }

    /**
     * Afficher l'historique des réservations
     */
    public function historique() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $stmt = $this->reservation->getByUserId($_SESSION['user_id']);
        $reservations = $stmt->fetchAll();

        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);

        require_once __DIR__ . '/../views/reservations/historique.php';
    }
}
