<?php
// ===== SCRIPT D'INSERTION D'UN UTILISATEUR =====

// ===== ÉTAPE 1 : RÉCUPÉRER LES DONNÉES ENVOYÉES PAR LE FORMULAIRE =====
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extraction des variables depuis le tableau
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$confirme_password = $data['confirme_password'] ?? null;

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

// ===== ÉTAPE 2 : SE CONNECTER À LA BASE DE DONNÉES =====

// Paramètres de connexion
$serveur = 'db';
$utilisateur = 'php_docker';
$motdepasse = 'password';
$base = 'appinfo';

try {
    // Se connecter à la base de données
    $mysqli = new mysqli($serveur, $utilisateur, $motdepasse, $base);
    
    if ($mysqli->connect_error) {
        throw new Exception("Erreur de connexion: " . $mysqli->connect_error);
    }
    
    $mysqli->set_charset("utf8");

    // ===== ÉTAPE 3 : VÉRIFIER SI L'EMAIL EXISTE DEJA =====
    
    $query = "SELECT email FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si num_rows > 0, c'est qu'on a trouvé un utilisateur avec cet email
    if ($result->num_rows > 0) {
        http_response_code(409);  // 409 = Conflit
        echo json_encode(['success' => false, 'message' => 'Cet email existe déjà']);
        exit();
    } 

    //===== ÉTAPE 4 : INSÉRER LE NOUVEL UTILISATEUR DANS LA BASE DE DONNÉES =====

    // Hasher le mot de passe avec password_hash (bcrypt) par l'algo PASSWORD_BCRYPT
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Préparer la requête d'insertion
    $query = "INSERT INTO users (email, password_hash) VALUES (?,?)";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Erreur de préparation: " . $mysqli->error);
    }
    // bind_param('ss', ...) = 2 paramètres string pour email et password_hash
    $stmt->bind_param('ss', $email, $hashed_password);
    
    if (!$stmt->execute()) {
        throw new Exception("Erreur lors de l'insertion: " . $stmt->error);
    }
    
    // ===== ÉTAPE 5 : RETOURNER LE SUCCÈS =====
    http_response_code(201);  // 201 = Created (créé avec succès)
    echo json_encode([
        'success' => true, 
        'message' => 'Compte créé avec succès !',
        'user' => [
            'email' => $email,
        ]
    ]);
    
    $stmt->close();
    $mysqli->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>
