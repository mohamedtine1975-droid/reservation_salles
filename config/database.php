<?php
// Classe de connexion à la base de données MySQL
// Utilise PDO pour les requêtes sécurisées

class Database {
    // Paramètres de connexion
    private $host = 'localhost';       // IP/nom du serveur MySQL
    private $db_name = 'reservation_salles'; // Nom de la base
    private $username = 'root';        // Utilisateur MySQL
    private $password = '';            // Mot de passe MySQL
    private $conn;                     // Objet de connexion

    // Créer et retourner la connexion à la base
    public function getConnection() {
        $this->conn = null;

        try {
            // Créer une connexion PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            // Afficher les erreurs en tant qu'exceptions
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Retourner les résultats sous forme d'array associatif
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Afficher l'erreur si la connexion échoue
            die("Erreur de connexion : " . $e->getMessage());
        }

        return $this->conn;
    }
}
