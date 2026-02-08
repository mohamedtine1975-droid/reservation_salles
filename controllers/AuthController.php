<?php
// Contrôleur d'authentification
// Gère l'inscription, connexion et déconnexion des utilisateurs

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;    // Connexion à la base
    private $user;  // Objet utilisateur

    // Initialiser les dépendances
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    // Afficher le formulaire d'inscription
    public function showRegister() {
        // Récupérer les messages et données d'erreurs de la session
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];
        unset($_SESSION['errors'], $_SESSION['old_data']);
        
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Traiter l'enregistrement d'un nouvel utilisateur
    public function register() {
        $errors = [];

        // Valider le nom
        if (empty($_POST['nom'])) {
            $errors[] = "Le nom est obligatoire.";
        }

        // Valider le prénom
        if (empty($_POST['prenom'])) {
            $errors[] = "Le prénom est obligatoire.";
        }

        // Valider l'email
        if (empty($_POST['email'])) {
            $errors[] = "L'email est obligatoire.";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }

        // Valider le mot de passe
        if (empty($_POST['mot_de_passe'])) {
            $errors[] = "Le mot de passe est obligatoire.";
        } elseif (strlen($_POST['mot_de_passe']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        // Valider la confirmation du mot de passe
        if (empty($_POST['confirmer_mot_de_passe'])) {
            $errors[] = "La confirmation du mot de passe est obligatoire.";
        } elseif ($_POST['mot_de_passe'] !== $_POST['confirmer_mot_de_passe']) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérifier si l'email n'existe pas déjà
        if (empty($errors)) {
            $this->user->email = $_POST['email'];
            if ($this->user->emailExists()) {
                $errors[] = "Cet email est déjà utilisé.";
            }
        }

        // Si erreurs, rediriger avec messages
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=register');
            exit;
        }

        // Créer l'utilisateur dans la base
        $this->user->nom = $_POST['nom'];
        $this->user->prenom = $_POST['prenom'];
        $this->user->email = $_POST['email'];
        $this->user->mot_de_passe = $_POST['mot_de_passe'];
        $this->user->role = 'utilisateur';

        // Vérifier si l'insertion a réussi
        if ($this->user->create()) {
            $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header('Location: index.php?action=login');
            exit;
        } else {
            $_SESSION['errors'] = ["Une erreur est survenue lors de l'inscription."];
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=register');
            exit;
        }
    }

    // Afficher le formulaire de connexion
    public function showLogin() {
        // Récupérer les messages et données d'erreurs de la session
        $errors = $_SESSION['errors'] ?? [];
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['errors'], $_SESSION['success']);
        
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Traiter la connexion d'un utilisateur
    public function login() {
        $errors = [];

        // Valider l'email
        if (empty($_POST['email'])) {
            $errors[] = "L'email est obligatoire.";
        }

        // Valider le mot de passe
        if (empty($_POST['mot_de_passe'])) {
            $errors[] = "Le mot de passe est obligatoire.";
        }

        // Si erreurs, rediriger avec messages
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?action=login');
            exit;
        }

        // Chercher l'utilisateur par email
        $this->user->email = $_POST['email'];
        
        if ($this->user->emailExists()) {
            // Vérifier le mot de passe haché
            if (password_verify($_POST['mot_de_passe'], $this->user->mot_de_passe)) {
                // Connexion réussie - stocker les infos en session
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_nom'] = $this->user->nom;
                $_SESSION['user_prenom'] = $this->user->prenom;
                $_SESSION['user_email'] = $this->user->email;
                $_SESSION['user_role'] = $this->user->role;
                
                header('Location: index.php?action=salles');
                exit;
            }
        }

        // Email ou mot de passe incorrect
        $_SESSION['errors'] = ["Email ou mot de passe incorrect."];
        header('Location: index.php?action=login');
        exit;
    }

    // Déconnecter l'utilisateur
    public function logout() {
        // Détruire la session
        session_destroy(); 
        header('Location: index.php?action=login');
        exit;
    }
}
