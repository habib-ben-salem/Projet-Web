<?php
// On importe les fonctions utiles depuis config.php
require_once 'config.php';

// Démarrer la session
startSession();

// Variable pour stocker les messages d'erreur
$error = '';

// On vérifie si l'utilisateur a cliqué sur le bouton "Se connecter" (formulaire envoyé)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère l'email et le mot de passe envoyés par le formulaire
    // Le ?? '' signifie : si la valeur n'existe pas, utiliser une chaîne vide
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Vérification que tous les champs sont remplis
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // On utilise un try-catch pour gérer les erreurs de base de données
        try {
            // Connexion à la base de données
            $pdo = getDbConnection();
            
            // Préparation de la requête SQL pour chercher l'utilisateur par email
            // Le ? est un paramètre sécurisé (protection contre les injections SQL)
            $stmt = $pdo->prepare('SELECT id, email, password_hash, role FROM users WHERE email = ?');
            
            // Exécution de la requête avec l'email fourni
            $stmt->execute([$email]);
            
            // Récupération des résultats (informations de l'utilisateur)
            $user = $stmt->fetch();
            
            // Vérification : l'utilisateur existe-t-il ET le mot de passe est-il correct ?
            // password_verify() compare le mot de passe saisi avec le mot de passe hashé en base
            if ($user && password_verify($password, $user['password_hash'])) {
                // Connexion réussie ! On lance une session utilisateur
                startSession();
                
                // Stockage des informations de l'utilisateur dans la session
                // Ces données resteront disponibles tant que l'utilisateur est connecté
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'] ?? 'user';  // 'user' ou 'admin'
                
                // Redirection vers la page d'accueil
                header('Location: /index.php');
                exit;
            } else {
                // Les identifiants sont incorrects (email non trouvé ou mot de passe faux)
                $error = 'Email ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de la base de données
            $error = 'Erreur lors de la connexion. Veuillez réessayer.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Catalogue de Voitures</title>
    <!-- On importe Bootstrap pour un design moderne et responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <!-- Carte avec un formulaire de connexion -->
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Connexion</h2>
                        
                        <!-- Affichage du message d'erreur s'il existe -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= escape($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Formulaire de connexion -->
                        <form method="POST" action="login.php">
                            <!-- Champ Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <!-- Champ Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <!-- Bouton de connexion -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                        
                        <!-- Lien pour retourner au catalogue -->
                        <div class="mt-3 text-center" style="display: flex; justify-content: space-between;">
                            <a href="index.php" class="text-decoration-none">🠔 Retour au catalogue</a>
                            <a href="/Create_account/index.php" class="text-decoration-none">Creer un compte 🠖</a>
                        </div>
                        
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- On importe le JavaScript de Bootstrap pour les fonctionnalités interactives -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
