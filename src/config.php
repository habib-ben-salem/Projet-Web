<?php
/*
 * ═══════════════════════════════════════════════════════════════════
 *  CONFIG.PHP - Le fichier de configuration principal du projet
 * ═══════════════════════════════════════════════════════════════════
 * 
 * Ce fichier contient :
 *   1. Les paramètres de connexion à la base de données MySQL
 *   2. Des fonctions utiles réutilisées dans tout le projet
 * 
 * Ce fichier est inclus au début de CHAQUE page PHP avec :
 *   require_once 'config.php';
 */

// ═══════════════════════════════════════════════════════════════════
// 1. PARAMÈTRES DE CONNEXION À LA BASE DE DONNÉES
// ═══════════════════════════════════════════════════════════════════

/*
 * define() : Crée une CONSTANTE (une variable qui ne change jamais)
 * 
 * Pourquoi des constantes ? Parce qu'on utilise ces valeurs partout dans
 * le projet, et elles ne doivent JAMAIS changer pendant l'exécution.
 */

// DB_HOST : Nom du serveur MySQL
// Dans Docker, le service MySQL s'appelle 'db' (voir docker-compose.yml)
define('DB_HOST', 'db');

// DB_NAME : Nom de la base de données qu'on utilise
define('DB_NAME', 'appinfo');

// DB_USER : Nom d'utilisateur pour se connecter à MySQL
define('DB_USER', 'php_docker');

// DB_PASS : Mot de passe pour se connecter à MySQL
define('DB_PASS', 'password');


// ═══════════════════════════════════════════════════════════════════
// 2. FONCTION : getDbConnection()
// ═══════════════════════════════════════════════════════════════════

/**
 * Cette fonction crée une connexion sécurisée à la base de données MySQL.
 * 
 * PDO (PHP Data Objects) : C'est la manière MODERNE et SÉCURISÉE de se
 * connecter à une base de données en PHP.
 * 
 * @return PDO  Retourne un objet PDO (connexion à la base de données)
 */
function getDbConnection() {
    // try-catch : Gère les erreurs possibles
    // Si quelque chose plante, on attrape l'erreur au lieu de crasher
    try {
        /*
         * DSN (Data Source Name) : La "recette" de connexion
         * Format : "mysql:host=serveur;dbname=base;charset=encodage"
         * 
         * Exemple résultat :
         * "mysql:host=db;dbname=appinfo;charset=utf8mb4"
         */
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        
        /*
         * Options de configuration PDO pour la sécurité et les bonnes pratiques
         */
        $options = [
            // ERRMODE_EXCEPTION : Si erreur SQL → lance une exception (qu'on peut attraper)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            
            // FETCH_ASSOC : Les résultats SQL seront des tableaux associatifs
            // Exemple : ['brand' => 'Porsche', 'model' => '911']
            // Au lieu de : [0 => 'Porsche', 1 => '911']
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            // EMULATE_PREPARES = false : Utilise de vraies requêtes préparées
            // Plus sécurisé contre les injections SQL
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        /*
         * Création de la connexion PDO
         * C'est ici qu'on se connecte réellement à MySQL
         */
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        // Retourne l'objet PDO pour l'utiliser dans les autres fichiers
        return $pdo;
        
    } catch (PDOException $e) {
        /*
         * PDOException : Erreur liée à la base de données
         * 
         * Si connexion échoue (mauvais mot de passe, serveur éteint, etc.)
         * on affiche un message et on arrête tout (die = exit)
         * 
         * Note : En PRODUCTION (site en ligne), il faudrait juste afficher
         * "Erreur de connexion" sans les détails (pour la sécurité)
         */
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}


// ═══════════════════════════════════════════════════════════════════
// 3. FONCTION : startSession()
// ═══════════════════════════════════════════════════════════════════

/**
 * Démarre une session PHP si elle n'est pas déjà démarrée.
 * 
 * SESSION : Un moyen de garder des informations sur un utilisateur
 * pendant qu'il navigue sur le site.
 * 
 * Exemple d'utilisation :
 *   $_SESSION['user_id'] = 5;  → L'utilisateur avec l'ID 5 est connecté
 *   $_SESSION['panier'] = [...];  → Son panier d'achats
 * 
 * Les données de session sont stockées CÔTÉ SERVEUR, pas dans le navigateur.
 * Le navigateur reçoit juste un ID de session (cookie).
 */
function startSession() {
    /*
     * session_status() : Vérifie l'état actuel de la session
     * 
     * Retourne :
     *   PHP_SESSION_NONE    → Aucune session démarrée
     *   PHP_SESSION_ACTIVE  → Session déjà active
     */
    if (session_status() === PHP_SESSION_NONE) {
        // Démarre la session seulement si elle n'existe pas encore
        // Évite l'erreur "session already started"
        session_start();
    }
}


// ═══════════════════════════════════════════════════════════════════
// 4. FONCTION : isLoggedIn()
// ═══════════════════════════════════════════════════════════════════

/**
 * Vérifie si un utilisateur est actuellement connecté.
 * 
 * Comment on sait qu'un utilisateur est connecté ?
 * → Si $_SESSION['user_id'] existe et n'est pas vide
 * 
 * Cette variable est créée dans login.php quand la connexion réussit.
 * 
 * @return bool  true = connecté, false = non connecté
 */
function isLoggedIn() {
    // D'abord, on s'assure que la session est démarrée
    startSession();
    
    /*
     * isset() : Vérifie si une variable existe
     * empty() : Vérifie si une variable est vide
     * 
     * On retourne true seulement si :
     *   - $_SESSION['user_id'] existe (isset)
     *   - ET elle n'est pas vide (! empty)
     * 
     * Exemple :
     *   $_SESSION['user_id'] = 5  → true (connecté)
     *   $_SESSION['user_id'] = 0  → true (connecté avec ID 0)
     *   $_SESSION non défini       → false (non connecté)
     */
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}


// ═══════════════════════════════════════════════════════════════════
// 5. FONCTION : requireLogin()
// ═══════════════════════════════════════════════════════════════════

/**
 * Force l'utilisateur à être connecté pour accéder à une page.
 * 
 * Si l'utilisateur N'EST PAS connecté → redirige vers login.php
 * Si l'utilisateur EST connecté → ne fait rien (laisse passer)
 * 
 * Utilisé dans : add.php, delete.php (pages nécessitant une connexion)
 */
function requireLogin() {
    // Vérifie si l'utilisateur est connecté
    if (!isLoggedIn()) {
        /*
         * header('Location: ...') : Redirige vers une autre page
         * 
         * Comment ça marche ?
         * 1. Envoie un header HTTP "Location: /login.php" au navigateur
         * 2. Le navigateur reçoit ce header
         * 3. Le navigateur charge automatiquement login.php
         * 
         * Important : Toujours mettre exit; après header()
         * Sinon le code continue à s'exécuter !
         */
        header('Location: /login.php');
        exit; // Arrête complètement l'exécution du script
    }
    // Si connecté → la fonction ne fait rien, le code continue normalement
}


// ═══════════════════════════════════════════════════════════════════
// 6. FONCTION : escape()
// ═══════════════════════════════════════════════════════════════════

/**
 * Échappe (sécurise) une chaîne de caractères avant de l'afficher en HTML.
 * 
 * POURQUOI ? Protection contre les attaques XSS (Cross-Site Scripting)
 * 
 * Exemple de DANGER :
 *   $name = "<script>alert('Hack!')</script>";
 *   echo $name;  → Le JavaScript s'exécute ! ❌
 * 
 * Solution avec escape() :
 *   echo escape($name);  → Affiche le texte sans exécuter le code ✅
 * 
 * @param string $data  La chaîne à sécuriser
 * @return string       La chaîne sécurisée
 */
function escape($data) {
    /*
     * htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
     * 
     * Conversions :
     *   <  →  &lt;    (less than)
     *   >  →  &gt;    (greater than)
     *   "  →  &quot;  (quote)
     *   '  →  &#039;  (apostrophe)
     *   &  →  &amp;   (ampersand)
     * 
     * Paramètres :
     *   ENT_QUOTES : Échappe aussi les guillemets simples et doubles
     *   'UTF-8'    : Encodage utilisé (important pour les accents)
     */
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/*
 * ═══════════════════════════════════════════════════════════════════
 * FIN DU FICHIER config.php
 * ═══════════════════════════════════════════════════════════════════
 * 
 * Résumé des fonctions disponibles :
 *   - getDbConnection()  → Se connecter à MySQL
 *   - startSession()     → Démarrer une session
 *   - isLoggedIn()       → Vérifier si connecté
 *   - requireLogin()     → Forcer la connexion
 *   - escape()           → Sécuriser l'affichage HTML
 */
