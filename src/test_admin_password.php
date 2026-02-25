<?php
// test_admin_password.php
// Place ce fichier sur ton hébergement distant dans le dossier src/ puis accède-y via le navigateur.
// Modifie les variables $email et $password_test ci-dessous :

require_once 'config.php';

$email = 'admin@tondomaine.com'; // <-- Mets ici l'email admin à tester
$password_test = 'ton_mot_de_passe'; // <-- Mets ici le mot de passe à tester

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare('SELECT id, email, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user) {
        echo "Utilisateur non trouvé pour l'email : $email";
    } else {
        if (password_verify($password_test, $user['password_hash'])) {
            echo "Mot de passe CORRECT pour $email (role: {$user['role']})";
        } else {
            echo "Mot de passe INCORRECT pour $email. Le hash en base ne correspond pas.";
        }
    }
} catch (Exception $e) {
    echo "Erreur de connexion ou SQL : ", $e->getMessage();
}
