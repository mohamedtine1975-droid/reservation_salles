<?php
// Modèle User - Gère les utilisateurs et l'authentification
// Interagit avec la table 'users' de la base de données

class User {
    private $conn;                   // Connexion à la base
    private $table = 'users';        // Nom de la table

    // Propriétés de l'utilisateur
    public $id;                      // ID unique
    public $nom;                     // Nom de l'utilisateur
    public $prenom;                  // Prénom de l'utilisateur
    public $email;                   // Email unique
    public $mot_de_passe;            // Mot de passe (haché)
    public $role;                    // Rôle (utilizateur/admin)
    public $date_creation;           // Date d'inscription

    // Initialiser la connexion à la base
    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouvel utilisateur dans la base
    public function create() {
        // Requête SQL d'insertion
        $query = "INSERT INTO " . $this->table . " 
                  (nom, prenom, email, mot_de_passe, role) 
                  VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données pour éviter les injections XSS
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = $this->role ?? 'utilisateur';

        // Hasher le mot de passe avec l'algorithme bcrypt
        $hashed_password = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);

        // Lier les paramètres pour éviter les injections SQL
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':role', $this->role);

        // Exécuter et retourner le résultat
        return $stmt->execute();
    }

    // Vérifier si un email existe et charger les données si oui
    public function emailExists() {
        // Requête SQL pour chercher l'utilisateur par email
        $query = "SELECT id, nom, prenom, email, mot_de_passe, role 
                  FROM " . $this->table . " 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        // Si l'utilisateur existe
        if ($stmt->rowCount() > 0) {
            // Charger les données de l'utilisateur
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

    // Récupérer un utilisateur par son ID
    public function getById($id) {
        // Requête SQL pour chercher l'utilisateur par ID
        $query = "SELECT id, nom, prenom, email, role, date_creation 
                  FROM " . $this->table . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Si l'utilisateur existe
        if ($stmt->rowCount() > 0) {
            // Charger les données
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
