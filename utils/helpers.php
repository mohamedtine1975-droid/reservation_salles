<?php
/**
 * Fonctions utilitaires
 */

/**
 * Protéger une page - rediriger si non connecté
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }
}

/**
 * Vérifier si l'utilisateur est admin
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrateur';
}

/**
 * Nettoyer une chaîne de caractères
 */
function clean($string) {
    return htmlspecialchars(strip_tags(trim($string)));
}

/**
 * Formater une date française
 */
function formatDateFr($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

/**
 * Formater une heure
 */
function formatTime($time) {
    return substr($time, 0, 5);
}
