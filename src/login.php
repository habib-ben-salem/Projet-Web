<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Connexion réussie
                startSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                
                header('Location: /index.php');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Connexion</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= escape($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <a href="index.php" class="text-decoration-none">← Retour au catalogue</a>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>Compte de test :</strong><br>
                                Email: admin@test.com<br>
                                Mot de passe: admin123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
