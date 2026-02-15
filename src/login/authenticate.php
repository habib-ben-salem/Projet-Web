<?php
// ===== DÉBUT DU SCRIPT D'AUTHENTIFICATION =====
// Ce fichier vérifie si l'email et le mot de passe saisis sont corrects dans la base de données

// ===== ÉTAPE 1 : RÉCUPÉRER LES DONNÉES ENVOYÉES PAR LE FORMULAIRE =====
// Quand le formulaire est soumis, les données arrivent en JSON (format texte structuré)
// Il faut les décoder pour les utiliser en PHP

$input = file_get_contents('php://input');
// file_get_contents('php://input') = lit le contenu brut envoyé par le navigateur
// C'est comme ouvrir une lettre et lire ce qu'il y a dedans

$data = json_decode($input, true);
// json_decode() = convertit le texte JSON en tableau PHP qu'on peut utiliser
// true = convertir en tableau (array) au lieu d'objet

// Extraction des variables depuis le tableau
$email = $data['email'] ?? null;
// $data['email'] = récupère la valeur de 'email' du formulaire
// ?? null = si 'email' n'existe pas, utiliser null (valeur vide)

$password = $data['password'] ?? null;
// $data['password'] = récupère la valeur de 'password' du formulaire

// Vérification rapide : est-ce que les deux champs sont remplis ?
if (!$email || !$password) {
    // Si email OU password sont vides, arrêter et envoyer une erreur
    http_response_code(400);
    // http_response_code(400) = code erreur 400 = "Mauvaise requête"
    echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis']);
    // json_encode() = convertit un tableau PHP en texte JSON pour envoyer au navigateur
    exit();
    // exit() = arrête l'exécution du script
}

// ===== ÉTAPE 2 : SE CONNECTER À LA BASE DE DONNÉES =====
// Nous devons nous connecter à MySQL pour accéder aux données des utilisateurs

// Paramètres de connexion (définis dans docker-compose.yml)
$serveur = 'db';
// 'db' = nom du service MySQL dans Docker (c'est comme une adresse réseau)
$utilisateur = 'php_docker';
// 'php_docker' = utilisateur MySQL créé dans le docker-compose
$motdepasse = 'password';
// 'password' = mot de passe de l'utilisateur MySQL
$base = 'users_accounts';
// 'users_accounts' = nom de la base de données créée par users.sql

try {
    // try {} = "essayer" d'exécuter ce code
    // Si une erreur survient, elle sera capturée par catch {}
    
    $mysqli = new mysqli($serveur, $utilisateur, $motdepasse, $base);
    // new mysqli() = crée une nouvelle connexion MySQL
    // Les 4 paramètres : serveur, utilisateur, mot de passe, base de données
    
    // Vérifier si la connexion a échoué
    if ($mysqli->connect_error) {
        // Si mysqli->connect_error existe, ça signifie qu'on ne s'est pas connecté
        throw new Exception("Erreur de connexion à la BD: " . $mysqli->connect_error);
        // throw = "lance" une erreur qu'on va catch
    }
    
    // Définir l'encodage en UTF-8 (pour supporter les accents, caractères spéciaux)
    $mysqli->set_charset("utf8");
    
} catch (Exception $e) {
    // catch = "attraper" l'erreur lancée par throw
    // Si on arrive ici, la connexion a échoué
    http_response_code(500);
    // 500 = erreur serveur
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' + $e]);
    exit();
}

// ===== ÉTAPE 3 : CHERCHER L'UTILISATEUR PAR SON EMAIL =====
// Maintenant qu'on est connecté, on fait une requête SQL pour chercher cet email

$query = "SELECT * FROM users WHERE email = ?";
// SELECT * FROM users = sélectionne TOUTES les colonnes de la table users
// WHERE email = ? = avec la condition : l'email égal à... (le ? sera remplacé par notre email)
// Le ? est un "placeholder" (un espace vide) pour la sécurité
// Raison : évite les "injections SQL" (attaques informatiques)

$stmt = $mysqli->prepare($query);
// $mysqli->prepare() = prépare la requête SQL
// C'est comme dire "Je veux faire cette requête, mais attends que je te donne l'email"

$stmt->bind_param('s', $email);
// bind_param() = lie un paramètre à la requête
// 's' = type "string" (texte) - on dit que le ? est du texte
// $email = la valeur à mettre à la place du ?

$stmt->execute();
// execute() = exécute la requête (la lance vraiment)
// MySQL cherche maintenant dans la table users

$result = $stmt->get_result();
// get_result() = récupère le résultat (les lignes trouvées)

// ===== ÉTAPE 4 : VÉRIFIER SI L'EMAIL EXISTE =====

if ($result->num_rows === 0) {
    // num_rows = nombre de lignes trouvées
    // === 0 = aucune ligne trouvée
    // Ça signifie : cet email n'existe pas dans la BD
    
    http_response_code(401);
    // 401 = Non autorisé
    echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
    // Note : on dit pas exactement "l'email n'existe pas" (pour la sécurité)
    exit();
}

$user = $result->fetch_assoc();
// fetch_assoc() = récupère la première ligne trouvée sous forme de tableau
// Par exemple : ['id' => 1, 'email' => 'test@mail.com', 'password' => 'hash...', ...]

// ===== ÉTAPE 5 : VÉRIFIER LE MOT DE PASSE =====
// Le mot de passe stocké en BD est "hashé" (chiffré)
// On ne peut pas comparer directement du texte brut avec du texte hashé
// Il faut utiliser password_verify()

$password_from_db = $user['password'];
// $user['password'] = le mot de passe hashé stocké en BD

if (!password_verify($password, $password_from_db)) {
    // password_verify() = vérifie que le mot de passe saisi correspond au hash
    // !password_verify = "Si la vérification ÉCHOUE" (le ! = NOT)
    
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
    exit();
}

// ===== ÉTAPE 6 : SUCCÈS ! L'UTILISATEUR EST AUTHENTIFIÉ =====
// Les deux identifiants sont corrects ! On peut accepter la connexion

http_response_code(200);
// 200 = OK, tout s'est bien passé

// Préparer les données à envoyer au navigateur (sans le mot de passe !)
$response = [
    'success' => true,
    'message' => 'Connexion réussie !',
    'user' => [
        'id' => $user['id'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name']
        // NOTE : on n'envoie PAS le mot de passe au navigateur !
    ]
];

echo json_encode($response);
// Convertir le tableau en JSON et l'envoyer au navigateur

// ===== FIN =====
// Le navigateur reçoit maintenant la réponse et peut décider quoi faire
// (rediriger vers la page d'accueil, afficher un message, etc.)
?>
