<?php
// Modèle Reservation - Gère les réservations des salles
// Interagit avec la table 'reservations' de la base de données

class Reservation {
    private $conn;                   // Connexion à la base
    private $table = 'reservations'; // Nom de la table

    // Propriétés de la réservation
    public $id;                      // ID unique
    public $user_id;                 // ID de l'utilisateur
    public $salle_id;                // ID de la salle
    public $date_reservation;        // Date de la réservation
    public $heure_debut;             // Heure de début
    public $heure_fin;               // Heure de fin
    public $statut;                  // Statut (confirmee/cancellée/etc)
    public $date_creation;           // Date d'enregistrement

    // Initialiser la connexion à la base
    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une nouvelle réservation dans la base
    public function create() {
        // Requête SQL d'insertion
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, salle_id, date_reservation, heure_debut, heure_fin, statut) 
                  VALUES (:user_id, :salle_id, :date_reservation, :heure_debut, :heure_fin, :statut)";

        $stmt = $this->conn->prepare($query);

        // Définir le statut par défaut
        $this->statut = $this->statut ?? 'confirmee';

        // Lier les paramètres pour éviter les injections SQL
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':salle_id', $this->salle_id);
        $stmt->bindParam(':date_reservation', $this->date_reservation);
        $stmt->bindParam(':heure_debut', $this->heure_debut);
        $stmt->bindParam(':heure_fin', $this->heure_fin);
        $stmt->bindParam(':statut', $this->statut);

        // Exécuter et retourner le résultat
        return $stmt->execute();
    }

    // Vérifier si une salle est disponible pour un créneau donné
    public function isAvailable($salle_id, $date, $heure_debut, $heure_fin, $exclude_id = null) {
        // Requête pour chercher les réservations qui chevauchent
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE salle_id = :salle_id 
                  AND date_reservation = :date 
                  AND statut != 'annulee'
                  AND (
                      (heure_debut < :heure_fin AND heure_fin > :heure_debut)
                  )";
        
        // Si on exclut une réservation (utile pour les modifications)
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

        // Retourner true si pas de conflit (0 résultats)
        return $stmt->rowCount() === 0;
    }

    // Récupérer toutes les réservations d'un utilisateur
    public function getByUserId($user_id) {
        // Requête avec jointure pour récupérer aussi le nom de la salle
        $query = "SELECT r.*, s.nom as salle_nom, s.localisation 
                  FROM " . $this->table . " r
                  INNER JOIN salles s ON r.salle_id = s.id
                  WHERE r.user_id = :user_id 
                  ORDER BY r.date_reservation DESC, r.heure_debut DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Retourner l'objet PDOStatement
        return $stmt;
    }

    // Récupérer toutes les réservations (admin)
    public function getAll() {
        // Requête avec jointures pour récupérer les infos salle et utilisateur
        $query = "SELECT r.*, s.nom as salle_nom, s.localisation,
                  u.nom as user_nom, u.prenom as user_prenom
                  FROM " . $this->table . " r
                  INNER JOIN salles s ON r.salle_id = s.id
                  INNER JOIN users u ON r.user_id = u.id
                  ORDER BY r.date_reservation DESC, r.heure_debut DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Retourner l'objet PDOStatement
        return $stmt;
    }

    // Récupérer une réservation par son ID
    public function getById($id) {
        // Requête pour chercher une réservation avec le nom de la salle
        $query = "SELECT r.*, s.nom as salle_nom 
                  FROM " . $this->table . " r
                  INNER JOIN salles s ON r.salle_id = s.id
                  WHERE r.id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Si la réservation existe
        if ($stmt->rowCount() > 0) {
            // Charger les données
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
