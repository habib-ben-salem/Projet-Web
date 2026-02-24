<?php
/*
 * ═══════════════════════════════════════════════════════════════════
 *  LOGOUT.PHP - Page de déconnexion
 * ═══════════════════════════════════════════════════════════════════
 * 
 * Cette page déconnecte l'utilisateur en détruisant sa session.
 * 
 * Comment on arrive ici ?
 *   - L'utilisateur clique sur le lien "Déconnexion" dans le menu
 * 
 * Que fait cette page ?
 *   1. Vide toutes les données de session
 *   2. Détruit complètement la session
 *   3. Redirige vers la page d'accueil
 * 
 * Note : Cette page n'affiche RIEN, elle redirige immédiatement
 */

// Inclut les fonctions utiles
require_once 'config.php';

// Démarre la session (nécessaire pour pouvoir la détruire)
startSession();

/*
 * ÉTAPE 1 : Vider toutes les variables de session
 * 
 * $_SESSION = array() : Remplace tout le contenu par un tableau vide
 * 
 * Avant :
 *   $_SESSION = ['user_id' => 5, 'user_email' => 'admin@test.com']
 * 
 * Après :
 *   $_SESSION = []
 */
$_SESSION = array();

/*
 * ÉTAPE 2 : Détruire complètement la session
 * 
 * session_destroy() : Détruit le fichier de session côté serveur
 * 
 * La différence avec $_SESSION = array() :
 *   - $_SESSION = array() : Vide les données mais garde la session active
 *   - session_destroy() : Supprime complètement la session du serveur
 * 
 * Les deux sont nécessaires pour une déconnexion complète et sécurisée
 */
session_destroy();

/*
 * ÉTAPE 3 : Rediriger vers la page d'accueil
 * 
 * L'utilisateur est maintenant déconnecté, on le renvoie à l'accueil
 */
header('Location: /index.php');
exit; // Important : Arrête l'exécution du script après la redirection

