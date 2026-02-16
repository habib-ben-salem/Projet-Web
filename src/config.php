<?php
/**
 * Configuration de la base de données
 */

// Paramètres de connexion MySQL
define('DB_HOST', 'db');
define('DB_NAME', 'appinfo');
define('DB_USER', 'php_docker');
define('DB_PASS', 'password');

/**
 * Connexion à la base de données avec PDO
 * @return PDO|null
 */
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // En production, ne pas afficher les détails de l'erreur
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

/**
 * Démarrer la session si ce n'est pas déjà fait
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Vérifier si l'utilisateur est connecté
 * @return bool
 */
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Rediriger vers la page de login si non connecté
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Échapper les données HTML pour éviter les failles XSS
 * @param string $data
 * @return string
 */
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
