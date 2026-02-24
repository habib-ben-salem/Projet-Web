<?php
/*
 * ═══════════════════════════════════════════════════════════════════
 *  COMPARE.PHP - Page de comparaison de voitures
 * ═══════════════════════════════════════════════════════════════════
 * 
 * Cette page permet de comparer plusieurs voitures côte à côte dans un tableau.
 * 
 * Comment on arrive ici ?
 *   1. Sur index.php, l'utilisateur coche plusieurs voitures
 *   2. Il clique sur le bouton "Comparer"
 *   3. Il est redirigé vers : compare.php?ids=1,2,3
 * 
 * Ce fichier va :
 *   1. Récupérer les IDs depuis l'URL
 *   2. Charger les voitures depuis la base de données
 *   3. Afficher un tableau comparatif
 */

// On inclut les fonctions utiles (connexion DB, sessions, etc.)
require_once 'config.php';

// Démarrer la session
startSession();

/*
 * SÉCURITÉ : Vérifier que l'utilisateur est connecté
 * 
 * requireLogin() : Si pas connecté → redirige vers login.php
 * Si connecté (user ou admin) → peut comparer
 */
requireLogin();


// ═══════════════════════════════════════════════════════════════════
// ÉTAPE 1 : RÉCUPÉRATION DES IDs DEPUIS L'URL
// ═══════════════════════════════════════════════════════════════════

/*
 * Exemple d'URL reçue : compare.php?ids=1,2,3
 * 
 * $_GET['ids'] contiendra : "1,2,3" (une chaîne de caractères)
 * 
 * ?? '' signifie : si 'ids' n'existe pas, utiliser une chaîne vide
 */
$ids = isset($_GET['ids']) ? $_GET['ids'] : '';

// Si aucun ID n'a été fourni dans l'URL → retour à l'accueil
if (empty($ids)) {
    header('Location: index.php');
    exit;
}


// ═══════════════════════════════════════════════════════════════════
// ÉTAPE 2 : TRANSFORMATION DE LA CHAÎNE EN TABLEAU D'IDs
// ═══════════════════════════════════════════════════════════════════

/*
 * explode() : Sépare une chaîne en tableau
 * 
 * Exemple :
 *   $ids = "1,2,3"
 *   explode(',', $ids)  →  ['1', '2', '3']
 */
$idArray = explode(',', $ids);

/*
 * array_map() : Applique une fonction à chaque élément d'un tableau
 * 
 * 'intval' : Convertit en nombre entier
 * 
 * Exemple :
 *   ['1', '2', '3']  →  [1, 2, 3]
 * 
 * Pourquoi ? Pour la SÉCURITÉ !
 * Si quelqu'un met compare.php?ids=1,abc,3
 * intval('abc') retournera 0, ce qui est sûr
 */
$idArray = array_map('intval', $idArray);

/*
 * array_filter() : Supprime les valeurs "fausses" (0, false, null, '')
 * 
 * Exemple :
 *   [1, 0, 2, 0, 3]  →  [1, 2, 3]
 * 
 * Pourquoi ? Si quelqu'un met des IDs invalides, on les vire
 */
$idArray = array_filter($idArray);

// Si après nettoyage il ne reste aucun ID valide → retour à l'accueil
if (empty($idArray)) {
    header('Location: index.php');
    exit;
}


// ═══════════════════════════════════════════════════════════════════
// ÉTAPE 3 : RÉCUPÉRATION DES VOITURES DEPUIS LA BASE DE DONNÉES
// ═══════════════════════════════════════════════════════════════════

try {
    // Connexion à la base de données
    $pdo = getDbConnection();
    
    /*
     * Création d'une requête SQL avec des placeholders (?)
     * 
     * Problème : On ne sait pas combien d'IDs on a (peut être 2, 3, 4...)
     * Solution : Créer autant de ? qu'il y a d'IDs
     * 
     * Si $idArray = [1, 2, 3], on veut :
     *   SELECT * FROM vehicles WHERE id IN (?, ?, ?)
     * 
     * str_repeat('?,', count($idArray) - 1) : Répète '?,' N-1 fois
     *   Exemple si 3 IDs : str_repeat('?,', 2) = '?,?,'
     * 
     * Puis on ajoute un dernier ? sans virgule
     *   '?,?,' + '?' = '?,?,?'
     */
    $placeholders = str_repeat('?,', count($idArray) - 1) . '?';
    
    /*
     * Préparation de la requête SQL
     * 
     * Exemple final :
     *   SELECT * FROM vehicles WHERE id IN (?,?,?)
     * 
     * IN (...) : Cherche si l'ID est dans la liste
     */
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id IN ($placeholders)");
    
    /*
     * Exécution de la requête avec les IDs
     * 
     * execute([1, 2, 3]) va remplacer les ? par 1, 2, 3
     * 
     * C'est SÉCURISÉ : PDO échappe automatiquement les valeurs
     * Protection contre les injections SQL !
     */
    $stmt->execute($idArray);
    
    /*
     * fetchAll() : Récupère TOUS les résultats
     * 
     * Retourne un tableau de voitures :
     * [
     *   ['id' => 1, 'brand' => 'Porsche', 'model' => '911', ...],
     *   ['id' => 2, 'brand' => 'Tesla', 'model' => 'Model S', ...],
     *   ...
     * ]
     */
    $vehicles = $stmt->fetchAll();
    
    // Si aucune voiture trouvée → retour à l'accueil
    if (empty($vehicles)) {
        header('Location: index.php');
        exit;
    }
    
} catch (PDOException $e) {
    // Si erreur de base de données → affiche l'erreur et arrête tout
    die('Erreur lors de la récupération des véhicules : ' . $e->getMessage());
}


// ═══════════════════════════════════════════════════════════════════
// ÉTAPE 4 : AFFICHAGE DE LA PAGE HTML
// ═══════════════════════════════════════════════════════════════════
// Tout le reste est du HTML/CSS pour afficher joliment les données
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparaison de Voitures</title>
    
    <!-- Importation de Bootstrap pour le design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles CSS personnalisés pour cette page -->
    <style>
        /* 
         * overflow-x: auto 
         * Si le tableau est trop large, ajoute une barre de défilement
         * horizontale au lieu de dépasser de la page
         */
        .comparison-table {
            overflow-x: auto;
        }
        
        /* Style pour les images dans le tableau */
        .comparison-table img {
            max-width: 100%;        /* L'image ne dépasse jamais de sa cellule */
            height: 200px;          /* Hauteur fixe de 200 pixels */
            object-fit: cover;      /* Coupe l'image pour garder les proportions */
        }
        
        /* Style pour les en-têtes de colonnes (les noms de voitures) */
        .comparison-table th {
            background-color: #0d6efd;  /* Bleu Bootstrap */
            color: white;
            font-weight: bold;
            padding: 15px;
        }
        
        /* Style pour toutes les cellules du tableau */
        .comparison-table td {
            padding: 15px;              /* Espace à l'intérieur de chaque cellule */
            vertical-align: middle;     /* Centre le contenu verticalement */
        }
        
        /* Style pour la première colonne (les labels comme "Marque", "Prix", etc.) */
        .row-label {
            background-color: #f8f9fa;  /* Gris très clair */
            font-weight: bold;          /* Texte en gras */
        }
    </style>
</head>
<body>
    <!-- ══════════════════════════════════════════════════════════════
         NAVBAR (Barre de navigation en haut)
         ══════════════════════════════════════════════════════════════ -->
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

    <!-- Header -->
    <header class="bg-success text-white text-center py-4">
        <div class="container">
            <h1 class="display-5">Comparaison de Voitures</h1>
            <p class="lead">Comparez <?= count($vehicles) ?> véhicule(s) côte à côte</p>
        </div>
    </header>

    <!-- ══════════════════════════════════════════════════════════════
         CONTENU PRINCIPAL - Tableau de comparaison
         ══════════════════════════════════════════════════════════════ -->
    <main class="container my-5">
        <!-- Bouton pour retourner au catalogue -->
        <div class="mb-3">
            <a href="index.php" class="btn btn-secondary">← Retour au catalogue</a>
        </div>

        <!-- Conteneur du tableau avec scroll horizontal si nécessaire -->
        <div class="comparison-table">
            <!--
                TABLEAU DE COMPARAISON
                
                Structure :
                - 1ère colonne : Nom de la caractéristique (Image, Marque, Prix...)
                - Colonnes suivantes : Une par voiture
                - 1ère ligne : En-têtes avec noms des voitures
                - Lignes suivantes : Une par caractéristique
            -->
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <!-- 1ère cellule vide (coin supérieur gauche) -->
                        <th style="width: 150px;">Caractéristique</th>
                        
                        <!--
                            Boucle foreach : Répète pour chaque voiture
                            
                            foreach ($vehicles as $vehicle) :
                            - Parcourt le tableau $vehicles
                            - À chaque tour, $vehicle contient les infos d'une voiture
                            - Crée une colonne par voiture
                        -->
                        <?php foreach ($vehicles as $vehicle): ?>
                            <th class="text-center">
                                <!-- Affiche "Porsche" sur une ligne -->
                                <?= escape($vehicle['brand']) ?><br>
                                <!-- Affiche "911 GT3" en petit dessous -->
                                <small><?= escape($vehicle['model']) ?></small>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- ═════════════════════════════════════════════════
                         LIGNE 1 : IMAGES
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <!-- Label de la ligne -->
                        <td class="row-label">Image</td>
                        
                        <!-- Une cellule par voiture -->
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <!-- Si la voiture a une image -->
                                <?php if ($vehicle['image_path']): ?>
                                    <img src="<?= escape($vehicle['image_path']) ?>" 
                                         alt="<?= escape($vehicle['brand'] . ' ' . $vehicle['model']) ?>"
                                         class="img-fluid">
                                <!-- Sinon, affiche un placeholder gris -->
                                <?php else: ?>
                                    <div class="bg-secondary text-white p-5">Pas d'image</div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNE 2 : MARQUE
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Marque</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center"><?= escape($vehicle['brand']) ?></td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNE 3 : MODÈLE
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Modèle</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center"><?= escape($vehicle['model']) ?></td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNE 4 : ANNÉE
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Année</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center"><?= escape($vehicle['year']) ?></td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNE 5 : PRIX
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Prix</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <span class="text-primary fw-bold fs-5">
                                    <!--
                                        number_format() : Formate un nombre
                                        
                                        Paramètres :
                                        - $vehicle['price'] : Le nombre à formater
                                        - 2 : Nombre de décimales
                                        - ',' : Séparateur de décimales
                                        - ' ' : Séparateur de milliers
                                        
                                        Exemple :
                                        196500.00 → "196 500,00"
                                    -->
                                    <?= number_format($vehicle['price'], 2, ',', ' ') ?> €
                                </span>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNE 6 : DESCRIPTION
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Description</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <!--
                                    ?? 'texte par défaut'
                                    
                                    Si description existe → l'affiche
                                    Si description est null → affiche 'Aucune description'
                                -->
                                <?= escape($vehicle['description'] ?? 'Aucune description') ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNES 7-11 : CARACTÉRISTIQUES DE PERFORMANCE
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Moteur</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <?= !empty($vehicle['engine']) ? escape($vehicle['engine']) : '-' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <tr>
                        <td class="row-label">Puissance</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <?= !empty($vehicle['power']) ? escape($vehicle['power']) . ' ch' : '-' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <tr>
                        <td class="row-label">Couple</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <?= !empty($vehicle['torque']) ? escape($vehicle['torque']) . ' N.m' : '-' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <tr>
                        <td class="row-label">Vitesse max</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <?= !empty($vehicle['maxSpeed']) ? escape($vehicle['maxSpeed']) . ' km/h' : '-' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <tr>
                        <td class="row-label">0-100 km/h</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <?= !empty($vehicle['zeroTOhundred']) ? escape($vehicle['zeroTOhundred']) . ' s' : '-' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- ═════════════════════════════════════════════════
                         LIGNE 12 : ACTIONS (Boutons)
                         ═════════════════════════════════════════════ -->
                    <tr>
                        <td class="row-label">Actions</td>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <td class="text-center">
                                <!-- Lien vers la page de détail de cette voiture -->
                                <a href="detail.php?id=<?= $vehicle['id'] ?>" 
                                   class="btn btn-sm btn-primary">Voir détails</a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bouton pour retourner au catalogue (en bas aussi) -->
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">← Retour au catalogue</a>
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
