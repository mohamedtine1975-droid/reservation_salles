<?php
// Fonctions utilitaires réutilisables dans toute l'application

// Vérifier que l'utilisateur est connecté, sinon rediriger vers login
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }
}

// Vérifier si l'utilisateur a les droits administrateur
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrateur';
}

// Nettoyer une chaîne de caractères pour éviter les injections XSS
// Supprime les balises HTML et échappe les caractères spéciaux
function clean($string) {
    return htmlspecialchars(strip_tags(trim($string)));
}

// Formater une date au format français (JJ/MM/AAAA)
function formatDateFr($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

// Formater une heure en récupérant seulement HH:MM
function formatTime($time) {
    return substr($time, 0, 5);
}
