<?php
// ===== SCRIPT D'INSERTION D'UN UTILISATEUR =====

// Inclure le fichier de configuration pour les fonctions de sécurité
require_once '../config.php';
startSession();

// ===== ÉTAPE 1 : RÉCUPÉRER LES DONNÉES ENVOYÉES PAR LE FORMULAIRE =====
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extraction des variables depuis le tableau
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$confirme_password = $data['confirme_password'] ?? null;
$role = $data['role'] ?? 'user';  // Par défaut 'user', peut être 'admin'

// Vérification rapide : tous les champs obligatoires
if (!$email || !$password || !$confirme_password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
    exit();
}

// Vérifier que les deux passwords sont identiques
if ($password !== $confirme_password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
    exit();
}

/*
 * SÉCURITÉ : Vérifier les droits pour créer un compte admin
 * 
 * Seuls les administrateurs peuvent créer d'autres comptes administrateur.
 * Les utilisateurs normaux ne peuvent créer que des comptes 'user'
 */
if ($role === 'admin' && !isAdmin()) {
    http_response_code(403);  // 403 = Forbidden (accès interdit)
    echo json_encode(['success' => false, 'message' => 'Vous n\'avez pas les droits pour créer un compte administrateur']);
    exit();
}

// ===== ÉTAPE 2 : SE CONNECTER À LA BASE DE DONNÉES =====

try {
    // Se connecter à la base de données en utilisant la fonction config.php
    $pdo = getDbConnection();

    // ===== ÉTAPE 3 : VÉRIFIER SI L'EMAIL EXISTE DEJA =====
    
    $query = "SELECT email FROM users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    
    // Si on trouve un résultat, l'email existe déjà
    if ($stmt->fetch()) {
        http_response_code(409);  // 409 = Conflit
        echo json_encode(['success' => false, 'message' => 'Cet email existe déjà']);
        exit();
    } 

    //===== ÉTAPE 4 : INSÉRER LE NOUVEL UTILISATEUR DANS LA BASE DE DONNÉES =====

    // Hasher le mot de passe avec password_hash (bcrypt) par l'algo PASSWORD_BCRYPT
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Préparer la requête d'insertion (avec le champ 'role')
    $query = "INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    
    // Exécuter avec l'email, le mot de passe hashé, et le rôle
    $stmt->execute([$email, $hashed_password, $role]);
    
    // ===== ÉTAPE 5 : RETOURNER LE SUCCÈS =====
    http_response_code(201);  // 201 = Created (créé avec succès)
    echo json_encode([
        'success' => true, 
        'message' => 'Compte créé avec succès !',
        'user' => [
            'email' => $email,
            'role' => $role
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>
