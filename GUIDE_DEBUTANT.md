# 📚 GUIDE COMPLET POUR DÉBUTANTS - Catalogue de Voitures

Bienvenue ! Ce guide explique TOUT ce qu'il y a dans ce projet, même si vous n'avez jamais fait de développement web.

---

## 📖 TABLE DES MATIÈRES

1. [Vue d'ensemble du projet](#1-vue-densemble-du-projet)
2. [Les technologies utilisées](#2-les-technologies-utilisées)
3. [Comment fonctionne Docker](#3-comment-fonctionne-docker)
4. [Structure de la base de données](#4-structure-de-la-base-de-données)
5. [Architecture du projet](#5-architecture-du-projet)
6. [Explication de chaque fichier](#6-explication-de-chaque-fichier)
7. [Concepts importants](#7-concepts-importants)
8. [Comment tester le projet](#8-comment-tester-le-projet)
9. [Glossaire des termes techniques](#9-glossaire-des-termes-techniques)

---

## 1. VUE D'ENSEMBLE DU PROJET

### Qu'est-ce que c'est ?
C'est un **site web de catalogue de voitures** où vous pouvez :
- 📋 Voir la liste des voitures disponibles
- 🔍 Voir les détails d'une voiture
- ➕ Ajouter des voitures (si vous êtes connecté)
- 🗑️ Supprimer des voitures (si vous êtes connecté)
- ⚖️ Comparer plusieurs voitures côte à côte
- 🔐 Se connecter avec un compte utilisateur

### Comment ça fonctionne ?
1. **Le navigateur** (Chrome, Firefox...) affiche les pages web
2. **Le serveur web** (Apache dans Docker) exécute le code PHP
3. **PHP** génère les pages HTML dynamiques
4. **MySQL** stocke les données (voitures, utilisateurs)
5. **Bootstrap** rend le site joli et responsive

---

## 2. LES TECHNOLOGIES UTILISÉES

### 🐘 PHP (langage de programmation)
**C'est quoi ?** Un langage qui s'exécute côté serveur pour créer des pages web dynamiques.

**Exemple concret :**
```php
<?php
echo "Bonjour !"; // Affiche "Bonjour !" sur la page
?>
```

**Pourquoi on l'utilise ?** Pour communiquer avec la base de données et générer des pages HTML différentes selon les données.

---

### 🗄️ MySQL (base de données)
**C'est quoi ?** Un système pour stocker des données dans des tableaux (tables).

**Exemple concret :**
```
Table "vehicles" (voitures)
+----+---------+-----------+------+----------+
| id | brand   | model     | year | price    |
+----+---------+-----------+------+----------+
| 1  | Porsche | 911 GT3   | 2024 | 196500.00|
| 2  | Tesla   | Model S   | 2024 | 109990.00|
+----+---------+-----------+------+----------+
```

**Pourquoi on l'utilise ?** Pour sauvegarder les voitures et les utilisateurs de manière permanente.

---

### 🐳 Docker (conteneurisation)
**C'est quoi ?** Un outil qui crée des "boîtes" isolées contenant tout ce dont on a besoin pour faire tourner l'application.

**Pourquoi on l'utilise ?** Pour que le projet fonctionne de la même manière sur tous les ordinateurs sans avoir à installer PHP et MySQL manuellement.

**Les 3 conteneurs de ce projet :**
1. **www** : Serveur web Apache + PHP
2. **db** : Serveur MySQL
3. **phpmyadmin** : Interface web pour gérer MySQL

---

### 🎨 Bootstrap (framework CSS)
**C'est quoi ?** Une bibliothèque de styles CSS déjà faits pour rendre un site beau rapidement.

**Exemple :**
```html
<button class="btn btn-primary">Cliquez ici</button>
```
Cette ligne crée automatiquement un beau bouton bleu !

---

### 🌐 HTML (structure des pages)
**C'est quoi ?** Le langage de base pour créer des pages web.

**Exemple :**
```html
<h1>Titre</h1>
<p>Ceci est un paragraphe.</p>
```

---

### ✨ JavaScript (interactivité)
**C'est quoi ?** Un langage qui s'exécute dans le navigateur pour rendre les pages interactives.

**Dans ce projet :** Utilisé pour la fonctionnalité de comparaison (cocher les cases, afficher le bouton, etc.)

---

## 3. COMMENT FONCTIONNE DOCKER

### Le fichier docker-compose.yml
Ce fichier décrit les 3 services (conteneurs) :

```yaml
services:
  www:                    # Service du serveur web
    build: .              # Construit l'image depuis Dockerfile
    ports:
      - "8080:80"         # Le port 80 du conteneur → port 8080 de votre PC
    volumes:
      - ./src:/var/www/html  # Synchronise le dossier src avec le conteneur

  db:                     # Service MySQL
    image: mysql:latest   # Utilise l'image MySQL officielle
    environment:
      - MYSQL_DATABASE=appinfo      # Nom de la base de données
      - MYSQL_USER=php_docker       # Nom d'utilisateur
      - MYSQL_PASSWORD=password     # Mot de passe

  phpmyadmin:            # Interface web pour MySQL
    ports:
      - 8001:80          # Accessible sur http://localhost:8001
```

### Commandes Docker importantes

```bash
# Démarrer tous les conteneurs
docker-compose up -d

# Arrêter tous les conteneurs
docker-compose down

# Voir les conteneurs en cours d'exécution
docker-compose ps
```

---

## 4. STRUCTURE DE LA BASE DE DONNÉES

### Table `users` (utilisateurs)
Stocke les comptes utilisateurs.

| Colonne        | Type    | Description                          |
|----------------|---------|--------------------------------------|
| id             | INT     | Identifiant unique (auto-incrémenté) |
| email          | VARCHAR | Adresse email de l'utilisateur       |
| password_hash  | VARCHAR | Mot de passe chiffré                 |
| created_at     | TIMESTAMP | Date de création du compte         |

**Exemple de données :**
```
| id | email            | password_hash                     | created_at          |
|----|------------------|-----------------------------------|---------------------|
| 1  | admin@test.com   | $2y$10$mDLuz/7.cZ...                | 2026-02-15 10:30:00 |
```

---

### Table `vehicles` (voitures)
Stocke toutes les voitures du catalogue.

| Colonne      | Type         | Description                        |
|--------------|--------------|------------------------------------|
| id           | INT          | Identifiant unique                 |
| brand        | VARCHAR(100) | Marque (ex: "Porsche")             |
| model        | VARCHAR(100) | Modèle (ex: "911 GT3")             |
| year         | INT          | Année de fabrication               |
| price        | DECIMAL      | Prix en euros                      |
| image_path   | VARCHAR(255) | URL de l'image                     |
| description  | TEXT         | Description détaillée              |
| created_at   | TIMESTAMP    | Date d'ajout                       |

**Exemple de données :**
```
| id | brand   | model     | year | price      | description               |
|----|---------|-----------|------|------------|---------------------------|
| 1  | Porsche | 911 GT3   | 2024 | 196500.00  | Voiture sportive...       |
| 2  | Tesla   | Model S   | 2024 | 109990.00  | Berline électrique...     |
```

---

## 5. ARCHITECTURE DU PROJET

```
dev-web-mobile/
├── docker-compose.yml    # Configuration Docker
├── Dockerfile            # Instructions pour créer l'image web
├── README.md             # Documentation
├── GUIDE_DEBUTANT.md     # Ce fichier !
│
├── db/                   # Scripts SQL
│   ├── database.sql      # Création des tables + données de test
│   └── users.sql         # (optionnel) Autres données utilisateurs
│
└── src/                  # Code source PHP
    ├── config.php        # Configuration et fonctions utiles
    ├── index.php         # Page d'accueil (liste des voitures)
    ├── login.php         # Page de connexion
    ├── logout.php        # Déconnexion
    ├── add.php           # Ajouter une voiture
    ├── detail.php        # Détails d'une voiture
    ├── delete.php        # Supprimer une voiture
    ├── compare.php       # Comparer des voitures
    │
    ├── Accueil/          # (ancien dossier, pas utilisé)
    ├── Create_account/   # Création de compte
    └── Img/              # Images locales
```

---

## 6. EXPLICATION DE CHAQUE FICHIER

### 📄 config.php - Le cerveau du projet
**Rôle :** Contient toutes les fonctions utiles réutilisées partout.

**Ce qu'il fait :**
1. **Connexion à la base de données**
   ```php
   function getDbConnection() {
       // Crée une connexion PDO à MySQL
   }
   ```

2. **Gestion des sessions**
   ```php
   function startSession() {
       // Démarre la session (permet de garder l'utilisateur connecté)
   }
   
   function isLoggedIn() {
       // Vérifie si l'utilisateur est connecté
   }
   ```

3. **Sécurité**
   ```php
   function escape($data) {
       // Empêche les attaques XSS en échappant le HTML
   }
   ```

---

### 🏠 index.php - Page d'accueil
**Rôle :** Affiche la liste de toutes les voitures.

**Étapes :**
1. Se connecte à la base de données
2. Récupère toutes les voitures (`SELECT * FROM vehicles`)
3. Affiche chaque voiture dans une carte (card)
4. Ajoute des cases à cocher pour la comparaison

**Parties importantes :**
- **PHP** (début du fichier) : Récupère les données
- **HTML** (milieu) : Structure de la page
- **CSS** (dans `<style>`) : Styles personnalisés
- **JavaScript** (fin) : Gestion des cases à cocher

---

### 🔐 login.php - Connexion
**Rôle :** Permet à un utilisateur de se connecter.

**Étapes :**
1. L'utilisateur entre son email et mot de passe
2. PHP vérifie dans la base de données si l'email existe
3. Si oui, on compare le mot de passe avec `password_verify()`
4. Si correct → crée une session et redirige vers l'accueil
5. Si incorrect → affiche un message d'erreur

**Sécurité :**
- Les mots de passe sont **hashés** (chiffrés) dans la base
- On utilise des **requêtes préparées** pour éviter les injections SQL

---

### ➕ add.php - Ajouter une voiture
**Rôle :** Permet d'ajouter une nouvelle voiture au catalogue.

**Étapes :**
1. Vérifie que l'utilisateur est connecté (`requireLogin()`)
2. Affiche un formulaire
3. Quand le formulaire est soumis :
   - Valide les données (tous les champs sont remplis ?)
   - Insère les données dans la table `vehicles`
   - Redirige vers la page de détail de la nouvelle voiture

**Validation :**
- Année entre 1900 et 2100
- Prix positif
- Marque et modèle non vides

---

### 🔍 detail.php - Détails d'une voiture
**Rôle :** Affiche toutes les informations d'une voiture spécifique.

**Étapes :**
1. Récupère l'ID depuis l'URL (`?id=1`)
2. Cherche la voiture dans la base de données
3. Affiche toutes ses informations (image, marque, modèle, prix, description)
4. Si connecté : affiche un bouton "Supprimer"

---

### 🗑️ delete.php - Supprimer une voiture
**Rôle :** Supprime une voiture de la base de données.

**Étapes :**
1. Vérifie que l'utilisateur est connecté
2. Récupère l'ID de la voiture à supprimer
3. Exécute `DELETE FROM vehicles WHERE id = ?`
4. Redirige vers l'accueil avec un message de succès

**Sécurité :**
- Seulement accessible si connecté
- Vérifie que la voiture existe avant de supprimer

---

### ⚖️ compare.php - Comparer des voitures
**Rôle :** Affiche plusieurs voitures côte à côte dans un tableau.

**Étapes :**
1. Récupère les IDs depuis l'URL (`?ids=1,2,3`)
2. Sépare la chaîne en tableau (`explode()`)
3. Récupère les voitures depuis la base
4. Affiche un tableau avec une ligne par caractéristique

**Exemple d'URL :**
```
compare.php?ids=1,2,3
```
Cela compare les voitures avec les IDs 1, 2 et 3.

---

### 🚪 logout.php - Déconnexion
**Rôle :** Déconnecte l'utilisateur.

**Étapes :**
1. Vide toutes les variables de session
2. Détruit la session complètement
3. Redirige vers l'accueil

---

## 7. CONCEPTS IMPORTANTS

### 🔄 Les Sessions PHP
**C'est quoi ?** Un moyen de garder des informations sur un utilisateur pendant sa visite.

**Comment ça marche ?**
```php
session_start();                    // Démarre la session
$_SESSION['user_id'] = 1;           // Stocke l'ID de l'utilisateur
echo $_SESSION['user_id'];          // Affiche : 1
```

**Utilisation dans le projet :**
- Savoir si l'utilisateur est connecté
- Stocker l'email de l'utilisateur
- Afficher des messages de succès/erreur

---

### 🛡️ PDO (PHP Data Objects)
**C'est quoi ?** Une manière sécurisée de communiquer avec MySQL.

**Requête DANGEREUSE (à ne jamais faire) :**
```php
$sql = "SELECT * FROM users WHERE email = '$email'";
// ❌ Vulnérable aux injections SQL !
```

**Requête SÉCURISÉE (avec PDO) :**
```php
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
// ✅ Sécurisé ! Le ? est échappé automatiquement
```

---

### 🔐 Hachage des mots de passe
**Pourquoi ?** On ne stocke JAMAIS les mots de passe en clair dans la base de données.

**Comment ça marche ?**
```php
// Créer un hash (lors de l'inscription)
$hash = password_hash('monMotDePasse', PASSWORD_DEFAULT);
// Résultat : "$2y$10$..."

// Vérifier un mot de passe (lors de la connexion)
if (password_verify('monMotDePasse', $hash)) {
    echo "Mot de passe correct !";
}
```

**Avantage :** Même si quelqu'un vole la base de données, il ne peut pas connaître les vrais mots de passe.

---

### 🌐 GET vs POST
**Deux façons d'envoyer des données au serveur :**

**GET** - Dans l'URL
```
detail.php?id=5
       ↑
   Données visibles dans l'URL
```
```php
$id = $_GET['id']; // Récupère 5
```

**POST** - Dans le corps de la requête (invisible)
```html
<form method="POST">
    <input name="email" value="test@test.com">
    <button type="submit">Envoyer</button>
</form>
```
```php
$email = $_POST['email']; // Récupère "test@test.com"
```

**Quand utiliser quoi ?**
- **GET** : Récupérer des données, filtrer, rechercher
- **POST** : Envoyer des données sensibles, créer/modifier/supprimer

---

### 🔀 Redirections
**C'est quoi ?** Envoyer l'utilisateur vers une autre page.

```php
header('Location: /index.php');
exit; // Important ! Arrête l'exécution du script
```

**Utilisé pour :**
- After login → aller vers l'accueil
- Après ajout d'une voiture → aller vers sa page de détail
- Si pas connecté → aller vers la page de login

---

### 🎯 Opérateur Null Coalescing `??`
**C'est quoi ?** Un raccourci pratique pour gérer les valeurs manquantes.

**Ancienne méthode :**
```php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = null;
}
```

**Nouvelle méthode (plus courte) :**
```php
$id = $_GET['id'] ?? null;
// Si $_GET['id'] existe → utilise sa valeur
// Sinon → utilise null
```

---

### 📤 Échappement HTML (XSS Protection)
**Problème :** Un utilisateur malveillant pourrait injecter du JavaScript.

**DANGER :**
```php
echo $name; // Si $name = "<script>alert('Hack!')</script>"
// ❌ Le JavaScript s'exécute !
```

**SOLUTION :**
```php
echo escape($name); // Convertit < en &lt; et > en &gt;
// ✅ Affiche le texte sans exécuter le code
```

**La fonction escape() :**
```php
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
```

---

### 🔄 SQL - Opérations CRUD
**CRUD** = Create, Read, Update, Delete (Créer, Lire, Modifier, Supprimer)

**CREATE (Créer) :**
```sql
INSERT INTO vehicles (brand, model, year, price) 
VALUES ('Tesla', 'Model 3', 2024, 45000);
```

**READ (Lire) :**
```sql
SELECT * FROM vehicles WHERE id = 1;
```

**UPDATE (Modifier) :**
```sql
UPDATE vehicles SET price = 50000 WHERE id = 1;
```

**DELETE (Supprimer) :**
```sql
DELETE FROM vehicles WHERE id = 1;
```

---

## 8. COMMENT TESTER LE PROJET

### Étape 1 : Démarrer Docker
```bash
cd c:\Projects\Projet_dev_web\dev-web-mobile
docker-compose up -d
```

Attendez environ 30 secondes que tout démarre.

---

### Étape 2 : Accéder au site
Ouvrez votre navigateur et allez sur :
```
http://localhost:8080/
```

Vous devriez voir la liste des voitures.

---

### Étape 3 : Se connecter
1. Cliquez sur "Connexion"
2. Utilisez ces identifiants de test :
   - **Email :** `admin@test.com`
   - **Mot de passe :** `admin123`

---

### Étape 4 : Tester les fonctionnalités

**Ajouter une voiture :**
1. Cliquez sur "➕ Ajouter une voiture"
2. Remplissez le formulaire
3. Cliquez sur "Ajouter"

**Voir les détails :**
1. Cliquez sur "Voir les détails" sur n'importe quelle voiture

**Comparer des voitures :**
1. Sur la page d'accueil, cochez 2 ou 3 voitures
2. Cliquez sur le bouton vert qui apparaît en bas à droite

**Supprimer une voiture :**
1. Allez sur la page de détail d'une voiture
2. Cliquez sur "Supprimer" (bouton rouge)

---

### Étape 5 : Voir la base de données
Pour voir directement les données dans MySQL :
```
http://localhost:8001
```

- **Serveur :** `db`
- **Utilisateur :** `php_docker`
- **Mot de passe :** `password`

---

## 9. GLOSSAIRE DES TERMES TECHNIQUES

| Terme | Explication simple |
|-------|-------------------|
| **Apache** | Le serveur web qui fait tourner PHP |
| **Backend** | La partie serveur (PHP + MySQL) |
| **Bootstrap** | Framework CSS pour faire de beaux designs |
| **Conteneur** | Une "boîte" Docker isolée |
| **CSS** | Langage pour styliser les pages web |
| **Docker** | Outil pour créer des environnements isolés |
| **Frontend** | La partie visible par l'utilisateur (HTML/CSS/JS) |
| **Hash** | Chiffrement irréversible (pour les mots de passe) |
| **HTML** | Langage de structure des pages web |
| **HTTP** | Protocole de communication web |
| **JavaScript** | Langage pour l'interactivité côté navigateur |
| **MySQL** | Système de gestion de base de données |
| **PDO** | Extension PHP pour communiquer avec MySQL |
| **PHP** | Langage de programmation côté serveur |
| **Port** | Numéro pour accéder à un service (ex: 8080) |
| **Requête préparée** | Requête SQL sécurisée avec PDO |
| **Session** | Données temporaires sur un utilisateur |
| **SQL** | Langage pour interroger les bases de données |
| **XSS** | Attaque par injection de JavaScript |

---

## 🎓 POUR ALLER PLUS LOIN

### Ressources d'apprentissage

**PHP :**
- [PHP.net - Documentation officielle](https://www.php.net/manual/fr/)
- [W3Schools PHP](https://www.w3schools.com/php/)

**SQL :**
- [SQL Tutorial](https://www.w3schools.com/sql/)
- [MySQL Documentation](https://dev.mysql.com/doc/)

**Docker :**
- [Docker Getting Started](https://docs.docker.com/get-started/)

**Bootstrap :**
- [Bootstrap Documentation](https://getbootstrap.com/docs/)

---

## ❓ QUESTIONS FRÉQUENTES

### Q1 : Pourquoi `require_once` au lieu de `require` ?
**R :** `require_once` inclut un fichier **une seule fois**. Si on l'inclut plusieurs fois par erreur, PHP l'ignore. Évite les erreurs de "function already defined".

---

### Q2 : C'est quoi `<?= ?>` ?
**R :** Un raccourci pour afficher une variable.
```php
<?= $name ?>
// Équivalent à :
<?php echo $name; ?>
```

---

### Q3 : Pourquoi mettre `exit` après `header()` ?
**R :** `header()` envoie une redirection, mais le code continue à s'exécuter ! `exit` arrête immédiatement le script.

---

### Q4 : C'est quoi `PDO::FETCH_ASSOC` ?
**R :** Récupère les résultats sous forme de tableau associatif (avec noms de colonnes comme clés).
```php
$vehicle = $stmt->fetch();
echo $vehicle['brand']; // "Porsche"
```

---

### Q5 : Pourquoi utiliser `trim()` ?
**R :** Enlève les espaces avant et après une chaîne.
```php
trim("  Porsche  "); // "Porsche"
```

---

## ✅ CHECKLIST DE COMPRÉHENSION

Cochez ce que vous avez compris :

- [ ] Je comprends à quoi sert Docker
- [ ] Je sais ce qu'est PHP
- [ ] Je comprends comment on se connecte à MySQL avec PDO
- [ ] Je sais ce qu'est une session
- [ ] Je comprends la différence entre GET et POST
- [ ] Je sais pourquoi on hash les mots de passe
- [ ] Je comprends comment fonctionne le login
- [ ] Je peux expliquer le rôle de config.php
- [ ] Je comprends comment on récupère des données de la base
- [ ] Je sais ce qu'est une injection SQL et comment l'éviter
- [ ] Je comprends la structure HTML d'une page
- [ ] Je sais comment Bootstrap rend le site joli

---

## 🆘 BESOIN D'AIDE ?

Si quelque chose n'est toujours pas clair :
1. Lisez les commentaires dans le code (ils expliquent chaque ligne)
2. Testez de modifier une petite chose et voyez ce qui se passe
3. Regardez les ressources d'apprentissage mentionnées plus haut
4. N'hésitez pas à poser des questions !

---

**Bon apprentissage ! 🚀**
