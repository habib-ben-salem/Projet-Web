<?php
require_once 'config.php';

// Démarrer la session
startSession();

// Vérifier que l'utilisateur est administrateur
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = $_POST['year'] ?? '';
    $price = $_POST['price'] ?? '';
    $image_path = trim($_POST['image_path'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $engine = trim($_POST['engine'] ?? '');
    $power = $_POST['power'] ?? '';
    $torque = $_POST['torque'] ?? '';
    $maxSpeed = $_POST['maxSpeed'] ?? '';
    $zeroTOhundred = $_POST['zeroTOhundred'] ?? '';
    
    // Validation
    if (empty($brand) || empty($model) || empty($year) || empty($price)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!is_numeric($year) || $year < 1900 || $year > 2100) {
        $error = 'L\'année doit être une valeur valide entre 1900 et 2100.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Le prix doit être un nombre positif.';
    } else {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare('
                INSERT INTO vehicles (brand, model, year, price, image_path, description, engine, power, torque, maxSpeed, zeroTOhundred) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $brand,
                $model,
                (int)$year,
                (float)$price,
                $image_path ?: null,
                $description ?: null,
                $engine ?: null,
                !empty($power) ? (int)$power : null,
                !empty($torque) ? (int)$torque : null,
                !empty($maxSpeed) ? (int)$maxSpeed : null,
                !empty($zeroTOhundred) ? (float)$zeroTOhundred : null
            ]);
            
            $success = 'Véhicule ajouté avec succès !';
            
            // Rediriger vers la page de détail du nouveau véhicule
            $newId = $pdo->lastInsertId();
            header('Location: /detail.php?id=' . $newId);
            exit;
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'ajout du véhicule : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une voiture - Catalogue Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚗 Catalogue Auto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="add.php">➕ Ajouter une voiture</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Create_account/index.php">👤 Créer un compte</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">👤 <?= escape($_SESSION['user_email']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="mb-4">Ajouter une nouvelle voiture</h1>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= escape($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <?= escape($success) ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-body p-4">
                        <form method="POST" action="add.php">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Marque <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="brand" 
                                           name="brand" 
                                           placeholder="Ex: Porsche, Tesla, BMW..."
                                           value="<?= isset($_POST['brand']) ? escape($_POST['brand']) : '' ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="model" class="form-label">Modèle <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="model" 
                                           name="model" 
                                           placeholder="Ex: 911 GT3, Model S..."
                                           value="<?= isset($_POST['model']) ? escape($_POST['model']) : '' ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="year" class="form-label">Année <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="year" 
                                           name="year" 
                                           min="1900" 
                                           max="2100" 
                                           placeholder="Ex: 2024"
                                           value="<?= isset($_POST['year']) ? escape($_POST['year']) : date('Y') ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Prix (€) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price" 
                                           name="price" 
                                           step="0.01" 
                                           min="0" 
                                           placeholder="Ex: 75000"
                                           value="<?= isset($_POST['price']) ? escape($_POST['price']) : '' ?>"
                                           required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image_path" class="form-label">URL de l'image</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="image_path" 
                                       name="image_path" 
                                       placeholder="https://example.com/image.jpg"
                                       value="<?= isset($_POST['image_path']) ? escape($_POST['image_path']) : '' ?>">
                                <div class="form-text">
                                    Optionnel. Vous pouvez utiliser des images depuis Unsplash, par exemple.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Décrivez les caractéristiques du véhicule..."><?= isset($_POST['description']) ? escape($_POST['description']) : '' ?></textarea>
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-3">Caractéristiques de performance (optionnel)</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="engine" class="form-label">Type de moteur</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="engine" 
                                           name="engine" 
                                           placeholder="Ex: Flat-6, Electric, V8 Biturbo..."
                                           value="<?= isset($_POST['engine']) ? escape($_POST['engine']) : '' ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="power" class="form-label">Puissance (ch)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="power" 
                                           name="power" 
                                           min="0" 
                                           placeholder="Ex: 510"
                                           value="<?= isset($_POST['power']) ? escape($_POST['power']) : '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="torque" class="form-label">Couple (N.m)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="torque" 
                                           name="torque" 
                                           min="0" 
                                           placeholder="Ex: 470"
                                           value="<?= isset($_POST['torque']) ? escape($_POST['torque']) : '' ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="maxSpeed" class="form-label">Vitesse max (km/h)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="maxSpeed" 
                                           name="maxSpeed" 
                                           min="0" 
                                           placeholder="Ex: 320"
                                           value="<?= isset($_POST['maxSpeed']) ? escape($_POST['maxSpeed']) : '' ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="zeroTOhundred" class="form-label">0-100 km/h (secondes)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="zeroTOhundred" 
                                       name="zeroTOhundred" 
                                       step="0.1" 
                                       min="0" 
                                       placeholder="Ex: 3.4"
                                       value="<?= isset($_POST['zeroTOhundred']) ? escape($_POST['zeroTOhundred']) : '' ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    ✓ Ajouter la voiture
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="fw-bold">💡 Conseil</h6>
                    <p class="mb-0 small">
                        Pour les images, vous pouvez utiliser des URLs d'images gratuites depuis 
                        <a href="https://unsplash.com/s/photos/car" target="_blank">Unsplash</a> 
                        en utilisant le format : <code>https://images.unsplash.com/photo-XXXXXX?w=500</code>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">© 2026 Catalogue Auto - Projet Universitaire</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
