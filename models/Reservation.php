<?php
/**
 * Modèle Reservation - Gestion des réservations
 */

class Reservation {
    private $conn;
    private $table = 'reservations';

    public $id;
    public $user_id;
    public $salle_id;
    public $date_reservation;
    public $heure_debut;
    public $heure_fin;
    public $statut;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Créer une nouvelle réservation
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, salle_id, date_reservation, heure_debut, heure_fin, statut) 
                  VALUES (:user_id, :salle_id, :date_reservation, :heure_debut, :heure_fin, :statut)";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->statut = $this->statut ?? 'confirmee';

        // Liaison des paramètres
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':salle_id', $this->salle_id);
        $stmt->bindParam(':date_reservation', $this->date_reservation);
        $stmt->bindParam(':heure_debut', $this->heure_debut);
        $stmt->bindParam(':heure_fin', $this->heure_fin);
        $stmt->bindParam(':statut', $this->statut);

        return $stmt->execute();
    }

    /**
     * Vérifier si une salle est disponible pour un créneau donné
     */
    public function isAvailable($salle_id, $date, $heure_debut, $heure_fin, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE salle_id = :salle_id 
                  AND date_reservation = :date 
                  AND statut != 'annulee'
                  AND (
                      (heure_debut < :heure_fin AND heure_fin > :heure_debut)
                  )";
        
        if ($exclude_id !== null) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':salle_id', $salle_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':heure_debut', $heure_debut);
        $stmt->bindParam(':heure_fin', $heure_fin);
        
        if ($exclude_id !== null) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }

        $stmt->execute();

        return $stmt->rowCount() === 0;
    }

    /**
     * Obtenir les réservations d'un utilisateur
     */
    public function getByUserId($user_id) {
        $query = "SELECT r.*, s.nom as salle_nom, s.localisation 
                  FROM " . $this->table . " r
                  INNER JOIN salles s ON r.salle_id = s.id
                  WHERE r.user_id = :user_id 
                  ORDER BY r.date_reservation DESC, r.heure_debut DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtenir toutes les réservations
     */
    public function getAll() {
        $query = "SELECT r.*, s.nom as salle_nom, s.localisation,
                  u.nom as user_nom, u.prenom as user_prenom
                  FROM " . $this->table . " r
                  INNER JOIN salles s ON r.salle_id = s.id
                  INNER JOIN users u ON r.user_id = u.id
                  ORDER BY r.date_reservation DESC, r.heure_debut DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtenir une réservation par ID
     */
    public function getById($id) {
        $query = "SELECT r.*, s.nom as salle_nom 
                  FROM " . $this->table . " r
                  INNER JOIN salles s ON r.salle_id = s.id
                  WHERE r.id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->salle_id = $row['salle_id'];
            $this->date_reservation = $row['date_reservation'];
            $this->heure_debut = $row['heure_debut'];
            $this->heure_fin = $row['heure_fin'];
            $this->statut = $row['statut'];
            return true;
        }

        return false;
    }
}
