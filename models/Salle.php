<?php
/**
 * Modèle Salle - Gestion des salles
 */

class Salle {
    private $conn;
    private $table = 'salles';

    public $id;
    public $nom;
    public $capacite;
    public $localisation;
    public $description;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtenir toutes les salles
     */
    public function getAll() {
        $query = "SELECT id, nom, capacite, localisation, description 
                  FROM " . $this->table . " 
                  ORDER BY nom ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtenir une salle par ID
     */
    public function getById($id) {
        $query = "SELECT id, nom, capacite, localisation, description, date_creation 
                  FROM " . $this->table . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->capacite = $row['capacite'];
            $this->localisation = $row['localisation'];
            $this->description = $row['description'];
            $this->date_creation = $row['date_creation'];
            return true;
        }

        return false;
    }

    /**
     * Vérifier si une salle existe
     */
    public function exists($id) {
        $query = "SELECT id FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
