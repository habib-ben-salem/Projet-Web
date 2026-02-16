<?php
require_once 'config.php';

startSession();

// Récupérer les messages de session
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;

// Supprimer les messages après les avoir récupérés
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query('SELECT * FROM vehicles ORDER BY created_at DESC');
    $vehicles = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Erreur lors de la récupération des véhicules : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Voitures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
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
                        <a class="nav-link active" href="index.php">Catalogue</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add.php">➕ Ajouter une voiture</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">👤 <?= escape($_SESSION['user_email']) ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Catalogue de Voitures</h1>
            <p class="lead">Découvrez notre sélection de véhicules d'exception</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= escape($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= escape($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($vehicles)): ?>
            <div class="alert alert-info text-center">
                <h4>Aucune voiture dans le catalogue</h4>
                <p>Le catalogue est vide pour le moment.</p>
                <?php if (isLoggedIn()): ?>
                    <a href="add.php" class="btn btn-primary">Ajouter la première voiture</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($vehicles as $vehicle): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <?php if ($vehicle['image_path']): ?>
                                <img src="<?= escape($vehicle['image_path']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= escape($vehicle['brand'] . ' ' . $vehicle['model']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center">
                                    <span class="text-white">Pas d'image</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= escape($vehicle['brand']) ?> <?= escape($vehicle['model']) ?>
                                </h5>
                                <p class="text-muted mb-2">Année: <?= escape($vehicle['year']) ?></p>
                                <p class="price mb-3">
                                    <?= number_format($vehicle['price'], 2, ',', ' ') ?> €
                                </p>
                                <?php if (!empty($vehicle['description'])): ?>
                                    <p class="card-text">
                                        <?= escape(substr($vehicle['description'], 0, 100)) ?>
                                        <?= strlen($vehicle['description']) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="detail.php?id=<?= $vehicle['id'] ?>" 
                                   class="btn btn-primary w-100">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
