<?php
/*
 * ═══════════════════════════════════════════════════════════════════
 *  INDEX.PHP - Page d'accueil du catalogue de voitures
 * ═══════════════════════════════════════════════════════════════════
 * 
 * C'est la page PRINCIPALE du site.
 * 
 * Elle affiche :
 *   - La liste de toutes les voitures disponibles
 *   - Des cases à cocher pour sélectionner des voitures à comparer
 *   - Un bouton flottant pour lancer la comparaison
 * 
 * Accessible à : http://localhost:8080/index.php
 */

// Inclut les fonctions utiles (connexion DB, sécurité, sessions...)
require_once 'config.php';

// Démarre la session (pour savoir si l'utilisateur est connecté)
startSession();


// ═══════════════════════════════════════════════════════════════════
// GESTION DES MESSAGES DE SUCCÈS / ERREUR
// ═══════════════════════════════════════════════════════════════════

/*
 * Ces messages sont stockés dans la SESSION par d'autres pages
 * 
 * Exemples :
 *   - Après avoir supprimé une voiture → message de succès
 *   - Après une erreur → message d'erreur
 * 
 * On les récupère, puis on les supprime pour qu'ils ne s'affichent qu'une fois
 */

// Récupère le message de succès (s'il existe)
// ?? null signifie : si n'existe pas, utiliser null
$success_message = $_SESSION['success_message'] ?? null;

// Récupère le message d'erreur (s'il existe)
$error_message = $_SESSION['error_message'] ?? null;

/*
 * unset() : Supprime une variable de la session
 * 
 * Pourquoi ? Si on ne supprime pas, le message s'affichera encore
 * la prochaine fois qu'on charge la page !
 */
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);


// ═══════════════════════════════════════════════════════════════════
// RÉCUPÉRATION DE TOUTES LES VOITURES DEPUIS LA BASE DE DONNÉES
// ═══════════════════════════════════════════════════════════════════

/*
 * try-catch : Gère les erreurs possibles de la base de données
 */
try {
    // Se connecte à MySQL
    $pdo = getDbConnection();
    
    /*
     * SELECT * FROM vehicles : Récupère TOUTES les colonnes de TOUTES les voitures
     * ORDER BY created_at DESC : Trie par date de création, les plus récentes d'abord
     * 
     * DESC = DESCending (décroissant) : du plus récent au plus ancien
     * ASC = ASCending (croissant) : du plus ancien au plus récent
     */
    $stmt = $pdo->query('SELECT * FROM vehicles ORDER BY created_at DESC');
    
    /*
     * fetchAll() : Récupère TOUS les résultats dans un tableau
     * 
     * $vehicles contiendra :
     * [
     *   ['id' => 1, 'brand' => 'Porsche', 'model' => '911 GT3', ...],
     *   ['id' => 2, 'brand' => 'Tesla', 'model' => 'Model S', ...],
     *   ...
     * ]
     */
    $vehicles = $stmt->fetchAll();
    
} catch (PDOException $e) {
    /*
     * Si erreur SQL → Arrête tout et affiche un message
     * 
     * En PRODUCTION (site en ligne), on devrait :
     *   - Logger l'erreur dans un fichier
     *   - Afficher un message générique à l'utilisateur
     *   - Ne PAS révéler les détails de l'erreur
     */
    die('Erreur lors de la récupération des véhicules : ' . $e->getMessage());
}


// ═══════════════════════════════════════════════════════════════════
// À PARTIR D'ICI : C'EST DU HTML (Structure de la page)
// ═══════════════════════════════════════════════════════════════════
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Voitures</title>
    
    <!-- Importation de Bootstrap (framework CSS pour le design) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ═════════════════════════════════════════════════════════
         STYLES CSS PERSONNALISÉS
         ═════════════════════════════════════════════════════════ -->
    <!-- ═════════════════════════════════════════════════════════
         STYLES CSS PERSONNALISÉS
         ═════════════════════════════════════════════════════════ -->
    <style>
        /* Style pour les images des voitures */
        .card-img-top {
            height: 200px;         /* Hauteur fixe de 200 pixels */
            object-fit: cover;     /* Remplit l'espace en gardant les proportions */
        }
        
        /* Style pour le prix (gros et bleu) */
        .price {
            font-size: 1.5rem;     /* Taille de police 1.5 fois la normale */
            font-weight: bold;     /* Texte en gras */
            color: #0d6efd;        /* Bleu Bootstrap */
        }
        
        /*
         * Case à cocher pour sélectionner une voiture
         * Position absolue = sort du flux normal, se positionne librement
         */
        .compare-checkbox {
            position: absolute;    /* Positionné par rapport à la carte */
            top: 10px;            /* 10px du haut de la carte */
            right: 10px;          /* 10px de la droite de la carte */
            width: 25px;
            height: 25px;
            z-index: 10;          /* Au-dessus des autres éléments */
            cursor: pointer;       /* Curseur en forme de main au survol */
        }
        
        /*
         * Bouton flottant "X voiture(s) à comparer"
         * Fixé en bas à droite de l'écran, même si on scroll
         */
        .compare-btn-fixed {
            position: fixed;       /* Position fixe, ne bouge pas au scroll */
            bottom: 30px;         /* 30px du bas de l'écran */
            right: 30px;          /* 30px de la droite de l'écran */
            z-index: 1000;        /* Au-dessus de tout le reste */
            display: none;        /* Caché par défaut (JavaScript l'affichera) */
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);  /* Ombre portée */
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
                        <div class="card h-100 shadow-sm" style="position: relative;">
                            <input type="checkbox" 
                                   class="form-check-input compare-checkbox" 
                                   data-id="<?= $vehicle['id'] ?>"
                                   data-name="<?= escape($vehicle['brand'] . ' ' . $vehicle['model']) ?>">
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

    <!-- ══════════════════════════════════════════════════════════════
         BOUTON FLOTTANT POUR COMPARER LES VOITURES
         ══════════════════════════════════════════════════════════════
         
         Ce bouton n'apparaît QUE si au moins une voiture est cochée.
         Il affiche le nombre de voitures sélectionnées.
         Quand on clique dessus, on va vers compare.php avec les IDs.
    -->
    <a href="#" id="compareBtn" class="btn btn-success btn-lg compare-btn-fixed">
        <!-- Le nombre de voitures sélectionnées (mis à jour par JavaScript) -->
        <span id="compareCount">0</span> voiture(s) à comparer
    </a>

    <!-- Importation de Bootstrap JavaScript (pour les menus déroulants, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ══════════════════════════════════════════════════════════════
         SCRIPT JAVASCRIPT POUR LA COMPARAISON
         ══════════════════════════════════════════════════════════════ -->
    <script>
        /*
         * ÉTAPE 1 : Récupérer les éléments HTML avec document.querySelector()
         * 
         * C'est comme faire un "cherche-et-trouve" dans la page HTML
         */
        
        // Récupère TOUTES les cases à cocher (celles avec la classe 'compare-checkbox')
        // querySelectorAll retourne une liste (NodeList), pas un seul élément
        const checkboxes = document.querySelectorAll('.compare-checkbox');
        
        // Récupère le bouton de comparaison (avec l'ID 'compareBtn')
        const compareBtn = document.getElementById('compareBtn');
        
        // Récupère le <span> qui affiche le nombre (avec l'ID 'compareCount')
        const compareCount = document.getElementById('compareCount');

        /*
         * ÉTAPE 2 : Ajouter un écouteur d'événement sur chaque case à cocher
         * 
         * addEventListener('change', fonction) :
         *   - Quand la case change d'état (cochée ↔ décochée)
         *   - Exécute la fonction updateCompareButton()
         */
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCompareButton);
        });

        /*
         * ÉTAPE 3 : Fonction qui met à jour le bouton de comparaison
         * 
         * Cette fonction est appelée chaque fois qu'on coche/décoche une case
         */
        function updateCompareButton() {
            /*
             * Sélectionne SEULEMENT les cases qui sont cochées
             * 
             * :checked est un sélecteur CSS spécial pour les éléments cochés
             */
            const selectedCheckboxes = document.querySelectorAll('.compare-checkbox:checked');
            
            // Compte combien il y en a
            const count = selectedCheckboxes.length;
            
            // Si au moins 1 voiture est sélectionnée
            if (count > 0) {
                /*
                 * Affiche le bouton
                 * 
                 * style.display = 'block' : Rend l'élément visible
                 * (rappel : dans le CSS, on avait mis display: none)
                 */
                compareBtn.style.display = 'block';
                
                /*
                 * Met à jour le texte avec le nombre
                 * 
                 * textContent : Change le contenu textuel d'un élément
                 * Exemple : si count = 3, affichera "3 voiture(s) à comparer"
                 */
                compareCount.textContent = count;
                
                /*
                 * Construire l'URL de comparaison
                 * 
                 * Array.from() : Convertit la NodeList en vrai tableau JavaScript
                 * .map() : Transforme chaque élément du tableau
                 * cb.dataset.id : Récupère l'attribut data-id de la case à cocher
                 * .join(',') : Joint les IDs avec des virgules
                 * 
                 * Exemple :
                 *   Si on coche les voitures 1, 2 et 5
                 *   ids = [1, 2, 5]
                 *   ids.join(',') = "1,2,5"
                 *   URL finale : compare.php?ids=1,2,5
                 */
                const ids = Array.from(selectedCheckboxes).map(cb => cb.dataset.id);
                compareBtn.href = 'compare.php?ids=' + ids.join(',');
                
            } else {
                /*
                 * Si aucune voiture n'est sélectionnée
                 * 
                 * Cache le bouton
                 */
                compareBtn.style.display = 'none';
            }
        }
    </script>
</body>
</html>
