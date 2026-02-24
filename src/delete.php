<?php
/*
 * ═══════════════════════════════════════════════════════════════════
 *  DELETE.PHP - Suppression d'une voiture
 * ═══════════════════════════════════════════════════════════════════
 * 
 * Cette page supprime une voiture de la base de données.
 * 
 * Comment on arrive ici ?
 *   1. Sur la page detail.php, l'utilisateur clique sur "Supprimer"
 *   2. Un formulaire invisible s'envoie vers delete.php en POST
 *   3. L'ID de la voiture est envoyé dans les données POST
 * 
 * Sécurité :
 *   - Accessible UNIQUEMENT aux utilisateurs connectés
 *   - Accessible UNIQUEMENT en méthode POST (pas en GET)
 *   - Vérifie que la voiture existe avant de la supprimer
 */

// Inclut les fonctions utiles
require_once 'config.php';

/*
 * SÉCURITÉ 1 : Vérifier que l'utilisateur est administrateur
 * 
 * requireAdmin() : Si pas admin → redirige vers index.php
 * Si admin → continue normalement
 */
requireAdmin();

/*
 * SÉCURITÉ 2 : Vérifier que c'est bien une requête POST
 * 
 * $_SERVER['REQUEST_METHOD'] : Contient 'GET' ou 'POST'
 * 
 * Pourquoi vérifier ?
 *   - GET : Visible dans l'URL, peut être modifié facilement
 *   - POST : Dans le corps de la requête, plus sécurisé pour les actions
 * 
 * Si quelqu'un essaie d'accéder directement à delete.php dans le navigateur,
 * c'est une requête GET → on le redirige vers l'accueil
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

/*
 * Récupérer l'ID de la voiture à supprimer
 * 
 * $_POST['id'] : Données envoyées par le formulaire
 * ?? null : Si 'id' n'existe pas, utiliser null
 */
$id = $_POST['id'] ?? null;

/*
 * VALIDATION : Vérifier que l'ID est valide
 * 
 * !$id : Si $id est null, 0, false ou vide
 * !is_numeric($id) : Si $id n'est pas un nombre
 * 
 * Si l'ID est invalide → retour à l'accueil
 * Empêche les tentatives de suppression sans ID ou avec ID bizarre
 */
if (!$id || !is_numeric($id)) {
    header('Location: /index.php');
    exit;
}


// ═══════════════════════════════════════════════════════════════════
// SUPPRESSION DE LA VOITURE
// ═══════════════════════════════════════════════════════════════════

try {
    // Connexion à la base de données
    $pdo = getDbConnection();
    
    /*
     * ÉTAPE 1 : Vérifier que la voiture existe
     * 
     * Pourquoi ?
     *   - Pour éviter d'essayer de supprimer quelque chose qui n'existe pas
     *   - Pour récupérer le nom de la voiture pour le message de confirmation
     */
    $stmt = $pdo->prepare('SELECT id, brand, model FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch();
    
    // Si la voiture n'existe pas → retour à l'accueil
    if (!$vehicle) {
        header('Location: /index.php');
        exit;
    }
    
    /*
     * ÉTAPE 2 : Supprimer la voiture
     * 
     * DELETE FROM vehicles WHERE id = ?
     *   - DELETE FROM : Supprimer dans la table
     *   - WHERE id = ? : Seulement la ligne avec cet ID
     * 
     * Si on oublie le WHERE, TOUTES les voitures seraient supprimées ! ⚠️
     */
    $stmt = $pdo->prepare('DELETE FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    
    /*
     * ÉTAPE 3 : Créer un message de succès
     * 
     * On stocke le message dans la SESSION pour l'afficher sur index.php
     * 
     * Pourquoi dans la session ?
     *   - On va rediriger vers index.php
     *   - Les variables PHP normales sont perdues lors d'une redirection
     *   - La session permet de garder des données entre deux requêtes
     */
    startSession();
    $_SESSION['success_message'] = 'Le véhicule ' . $vehicle['brand'] . ' ' . 
                                     $vehicle['model'] . ' a été supprimé avec succès.';
    
    // Redirection vers l'accueil (qui affichera le message)
    header('Location: /index.php');
    exit;
    
} catch (PDOException $e) {
    /*
     * En cas d'erreur SQL (rare, mais possible)
     * 
     * Exemples d'erreurs possibles :
     *   - Connexion à la base perdue
     *   - Problème de permissions
     *   - Contrainte de clé étrangère (si on avait une relation avec une autre table)
     */
    startSession();
    $_SESSION['error_message'] = 'Erreur lors de la suppression du véhicule.';
    
    // Redirige vers la page de détail de la voiture (qui affichera l'erreur)
    header('Location: /detail.php?id=' . $id);
    exit;
}

