<?php
// ===== SCRIPT D'INSERTION D'UN UTILISATEUR DE TEST =====
// À exécuter une fois, puis supprimer pour la sécurité

// Paramètres de connexion
$serveur = 'db';
$utilisateur = 'php_docker';
$motdepasse = 'password';
$base = 'users_accounts';

// Données du nouvel utilisateur
$email = 'test.mro@test.com';
$password = '1234';
$first_name = 'Test';
$last_name = 'User';
$account_level = 1; // user (ID 1 dans la table administrators)

try {
    // Se connecter à la base de données
    $mysqli = new mysqli($serveur, $utilisateur, $motdepasse, $base);
    
    if ($mysqli->connect_error) {
        throw new Exception("Erreur de connexion: " . $mysqli->connect_error);
    }
    
    $mysqli->set_charset("utf8");
    
    // Hasher le mot de passe avec password_hash (bcrypt)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    // PASSWORD_BCRYPT = algorithme de chiffrement sécurisé
    
    // Préparer la requête d'insertion
    $query = "INSERT INTO users (email, password, first_name, last_name, account_level) VALUES (?, ?, ?, ?, ?)";
    // ?, ?, ?, ?, ? = 5 placeholders pour les 5 valeurs
    
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Erreur de préparation: " . $mysqli->error);
    }
    
    // Lier les paramètres
    $stmt->bind_param('ssssi', $email, $hashed_password, $first_name, $last_name, $account_level);
    // s = string, s = string, s = string, s = string, i = integer
    
    // Exécuter la requête
    if ($stmt->execute()) {
        echo "✅ Utilisateur inséré avec succès !<br>";
        echo "Email: " . $email . "<br>";
        echo "Password: " . $password . "<br>";
        echo "ID: " . $stmt->insert_id . "<br>";
        echo "<br><strong>⚠️ Note:</strong> Supprime ce fichier (insert_test_user.php) après usage pour la sécurité !";
    } else {
        throw new Exception("Erreur lors de l'insertion: " . $stmt->error);
    }
    
    $stmt->close();
    $mysqli->close();
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
