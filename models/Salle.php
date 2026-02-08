<?php
// Modèle Salle - Gère les salles disponibles
// Interagit avec la table 'salles' de la base de données

class Salle {
    private $conn;                   // Connexion à la base
    private $table = 'salles';       // Nom de la table

    // Propriétés de la salle
    public $id;                      // ID unique
    public $nom;                     // Nom de la salle
    public $capacite;                // Nombre de places
    public $localisation;            // Lieu/étage
    public $description;             // Description
    public $date_creation;           // Date de création

    // Initialiser la connexion à la base
    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer toutes les salles triées par nom
    public function getAll() {
        // Requête SQL pour récupérer toutes les salles
        $query = "SELECT id, nom, capacite, localisation, description 
                  FROM " . $this->table . " 
                  ORDER BY nom ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Retourner l'objet PDOStatement
        return $stmt;
    }

    // Récupérer une salle par son ID
    public function getById($id) {
        // Requête SQL pour chercher une salle par ID
        $query = "SELECT id, nom, capacite, localisation, description, date_creation 
                  FROM " . $this->table . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Si la salle existe
        if ($stmt->rowCount() > 0) {
            // Charger les données
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

    // Vérifier si une salle existe sans charger tous ses détails
    public function exists($id) {
        // Requête SQL simple pour vérifier l'existence
        $query = "SELECT id FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Retourner true si trouvée, false sinon
        return $stmt->rowCount() > 0;
    }
}
