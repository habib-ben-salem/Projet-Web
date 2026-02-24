<?php
require_once 'config.php';

startSession();

// Récupérer l'ID du véhicule
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: /index.php');
    exit;
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch();
    
    if (!$vehicle) {
        header('Location: /index.php');
        exit;
    }
} catch (PDOException $e) {
    die('Erreur lors de la récupération du véhicule : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($vehicle['brand'] . ' ' . $vehicle['model']) ?> - Catalogue Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .vehicle-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
        }
        .price {
            font-size: 2rem;
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
                        <a class="nav-link" href="index.php">Catalogue</a>
                    </li>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add.php">➕ Ajouter une voiture</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Create_account/index.php">👤 Créer un compte</a>
                        </li>
                    <?php endif; ?>
                    <?php if (isLoggedIn()): ?>
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

    <!-- Main Content -->
    <main class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Catalogue</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= escape($vehicle['brand'] . ' ' . $vehicle['model']) ?>
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <?php if ($vehicle['image_path']): ?>
                    <img src="<?= escape($vehicle['image_path']) ?>" 
                         class="vehicle-image shadow" 
                         alt="<?= escape($vehicle['brand'] . ' ' . $vehicle['model']) ?>">
                <?php else: ?>
                    <div class="vehicle-image bg-secondary d-flex align-items-center justify-content-center shadow">
                        <span class="text-white fs-3">Pas d'image disponible</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-6">
                <h1 class="display-4 mb-3">
                    <?= escape($vehicle['brand']) ?> <?= escape($vehicle['model']) ?>
                </h1>
                
                <div class="price mb-4">
                    <?= number_format($vehicle['price'], 2, ',', ' ') ?> €
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Caractéristiques</h5>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Marque :</th>
                                    <td><?= escape($vehicle['brand']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Modèle :</th>
                                    <td><?= escape($vehicle['model']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Année :</th>
                                    <td><?= escape($vehicle['year']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Moteur :</th>
                                    <td><?= !empty($vehicle['engine']) ? escape($vehicle['engine']) : 'Non spécifié' ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Puissance :</th>
                                    <td><?= !empty($vehicle['power']) ? escape($vehicle['power']) . ' ch' : 'Non spécifié' ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Couple :</th>
                                    <td><?= !empty($vehicle['torque']) ? escape($vehicle['torque']) . ' N.m' : 'Non spécifié' ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Vitesse max :</th>
                                    <td><?= !empty($vehicle['maxSpeed']) ? escape($vehicle['maxSpeed']) . ' km/h' : 'Non spécifié' ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">0-100 km/h :</th>
                                    <td><?= !empty($vehicle['zeroTOhundred']) ? escape($vehicle['zeroTOhundred']) . ' s' : 'Non spécifié' ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">ID :</th>
                                    <td>#<?= escape($vehicle['id']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (!empty($vehicle['description'])): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Description</h5>
                            <p class="card-text"><?= nl2br(escape($vehicle['description'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-2">
                    <a href="index.php" class="btn btn-secondary">
                        ← Retour au catalogue
                    </a>
                    
                    <?php if (isAdmin()): ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            🗑️ Supprimer
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de confirmation de suppression -->
    <?php if (isAdmin()): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette voiture ?</p>
                    <p class="fw-bold"><?= escape($vehicle['brand']) ?> <?= escape($vehicle['model']) ?> (<?= escape($vehicle['year']) ?>)</p>
                    <p class="text-danger">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" action="delete.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $vehicle['id'] ?>">
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">© 2026 Catalogue Auto - Projet Universitaire</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
