<?php
/**
 * Contrôleur d'authentification
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    /**
     * Afficher la page d'inscription
     */
    public function showRegister() {
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];
        unset($_SESSION['errors'], $_SESSION['old_data']);
        
        require_once __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Traiter l'inscription
     */
    public function register() {
        $errors = [];

        // Validation des champs
        if (empty($_POST['nom'])) {
            $errors[] = "Le nom est obligatoire.";
        }

        if (empty($_POST['prenom'])) {
            $errors[] = "Le prénom est obligatoire.";
        }

        if (empty($_POST['email'])) {
            $errors[] = "L'email est obligatoire.";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }

        if (empty($_POST['mot_de_passe'])) {
            $errors[] = "Le mot de passe est obligatoire.";
        } elseif (strlen($_POST['mot_de_passe']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        if (empty($_POST['confirmer_mot_de_passe'])) {
            $errors[] = "La confirmation du mot de passe est obligatoire.";
        } elseif ($_POST['mot_de_passe'] !== $_POST['confirmer_mot_de_passe']) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérifier si l'email existe déjà
        if (empty($errors)) {
            $this->user->email = $_POST['email'];
            if ($this->user->emailExists()) {
                $errors[] = "Cet email est déjà utilisé.";
            }
        }

        // S'il y a des erreurs, retourner au formulaire
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: index.php?action=register');
            exit;
        }

        // Créer l'utilisateur
        $this->user->nom = $_POST['nom'];
        $this->user->prenom = $_POST['prenom'];
        $this->user->email = $_POST['email'];
        $this->user->mot_de_passe = $_POST['mot_de_passe'];
        $this->user->role = 'utilisateur';

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

    /**
     * Afficher la page de connexion
     */
    public function showLogin() {
        $errors = $_SESSION['errors'] ?? [];
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['errors'], $_SESSION['success']);
        
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Traiter la connexion
     */
    public function login() {
        $errors = [];

        // Validation des champs
        if (empty($_POST['email'])) {
            $errors[] = "L'email est obligatoire.";
        }

        if (empty($_POST['mot_de_passe'])) {
            $errors[] = "Le mot de passe est obligatoire.";
        }

        // S'il y a des erreurs, retourner au formulaire
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?action=login');
            exit;
        }

        // Vérifier les identifiants
        $this->user->email = $_POST['email'];
        
        if ($this->user->emailExists()) {
            // Vérifier le mot de passe
            if (password_verify($_POST['mot_de_passe'], $this->user->mot_de_passe)) {
                // Connexion réussie
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_nom'] = $this->user->nom;
                $_SESSION['user_prenom'] = $this->user->prenom;
                $_SESSION['user_email'] = $this->user->email;
                $_SESSION['user_role'] = $this->user->role;
                
                header('Location: index.php?action=salles');
                exit;
            }
        }

        // Identifiants incorrects
        $_SESSION['errors'] = ["Email ou mot de passe incorrect."];
        header('Location: index.php?action=login');
        exit;
    }

    /**
     * Déconnexion
     */
    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
