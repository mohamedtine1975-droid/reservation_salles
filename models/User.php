<?php
/**
 * Modèle User - Gestion des utilisateurs
 */

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $role;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (nom, prenom, email, mot_de_passe, role) 
                  VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = $this->role ?? 'utilisateur';

        // Hachage du mot de passe
        $hashed_password = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);

        // Liaison des paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }

    /**
     * Vérifier si l'email existe déjà
     */
    public function emailExists() {
        $query = "SELECT id, nom, prenom, email, mot_de_passe, role 
                  FROM " . $this->table . " 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->mot_de_passe = $row['mot_de_passe'];
            $this->role = $row['role'];
            return true;
        }

        return false;
    }

    /**
     * Obtenir un utilisateur par ID
     */
    public function getById($id) {
        $query = "SELECT id, nom, prenom, email, role, date_creation 
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
            $this->prenom = $row['prenom'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->date_creation = $row['date_creation'];
            return true;
        }

        return false;
    }
}
