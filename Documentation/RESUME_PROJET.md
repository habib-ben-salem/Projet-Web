# 📝 RÉSUMÉ RAPIDE DU PROJET

Un guide ultra-rapide pour comprendre chaque fichier du projet.

---

## 🎯 OBJECTIF DU PROJET

Créer un **catalogue de voitures** en ligne où on peut :
- Voir toutes les voitures
- Ajouter/Supprimer des voitures (si connecté)
- Comparer des voitures côte à côte

---

## 📂 FICHIERS PRINCIPAUX (src/)

### ⭐ config.php - Le cerveau du projet
**Rôle :** Contient des fonctions utilisées partout

**Fonctions importantes :**
- `getDbConnection()` → Se connecter à MySQL
- `startSession()` → Démarrer une session PHP
- `isLoggedIn()` → Savoir si l'utilisateur est connecté
- `requireLogin()` → Forcer la connexion pour certaines pages
- `escape()` → Sécuriser l'affichage HTML

**À retenir :** Tous les fichiers incluent `config.php` en premier

---

### 🏠 index.php - Page d'accueil
**Rôle :** Affiche la liste de toutes les voitures

**Ce qu'il fait :**
1. Récupère toutes les voitures depuis MySQL
2. Les affiche dans des cartes (grille Bootstrap)
3. Ajoute des cases à cocher pour la comparaison
4. Affiche un bouton flottant "Comparer" (JavaScript)

**Accessible à :** http://localhost:8080/

**Connexion requise :** ❌ Non

---

### 🔐 login.php - Se connecter
**Rôle :** Permet à un utilisateur de se connecter

**Ce qu'il fait :**
1. Affiche un formulaire (email + mot de passe)
2. Vérifie dans la base de données si l'email existe
3. Compare le mot de passe avec `password_verify()`
4. Si OK → crée une session et redirige vers l'accueil
5. Si KO → affiche une erreur

**Connexion requise :** ❌ Non

---

### 🚪 logout.php - Se déconnecter
**Rôle :** Déconnecte l'utilisateur

**Ce qu'il fait :**
1. Vide toutes les variables de session
2. Détruit la session
3. Redirige vers l'accueil

**Connexion requise :** ❌ Non (mais inutile si pas connecté)

---

### ➕ add.php - Ajouter une voiture
**Rôle :** Permet d'ajouter une nouvelle voiture au catalogue

**Ce qu'il fait :**
1. Vérifie que l'utilisateur est connecté (sinon redirige)
2. Affiche un formulaire (marque, modèle, année, prix, image, description)
3. Valide les données (année entre 1900-2100, prix positif, etc.)
4. Insère la voiture dans MySQL
5. Redirige vers la page de détail de la nouvelle voiture

**Connexion requise :** ✅ Oui

---

### 🔍 detail.php - Détails d'une voiture
**Rôle :** Affiche toutes les infos d'une voiture spécifique

**Ce qu'il fait :**
1. Récupère l'ID depuis l'URL (`?id=5`)
2. Cherche la voiture dans MySQL
3. Affiche toutes ses infos (marque, modèle, année, prix, description, image)
4. Si connecté : affiche un bouton "Supprimer"

**URL exemple :** http://localhost:8080/detail.php?id=1

**Connexion requise :** ❌ Non (mais le bouton Supprimer n'apparaît que si connecté)

---

### 🗑️ delete.php - Supprimer une voiture
**Rôle :** Supprime une voiture de la base de données

**Ce qu'il fait :**
1. Vérifie que l'utilisateur est connecté
2. Vérifie que la requête est en POST (pas GET)
3. Récupère l'ID de la voiture
4. Vérifie que la voiture existe
5. Supprime la voiture de MySQL
6. Redirige vers l'accueil avec un message de succès

**Connexion requise :** ✅ Oui

---

### ⚖️ compare.php - Comparer des voitures
**Rôle :** Affiche plusieurs voitures côte à côte dans un tableau

**Ce qu'il fait :**
1. Récupère les IDs depuis l'URL (`?ids=1,2,3`)
2. Sépare la chaîne en tableau
3. Récupère les voitures depuis MySQL
4. Affiche un tableau comparatif (image, marque, modèle, année, prix, description)

**URL exemple :** http://localhost:8080/compare.php?ids=1,2,3

**Connexion requise :** ❌ Non

---

## 🗄️ FICHIERS DE BASE DE DONNÉES (db/)

### database.sql
**Rôle :** Crée la base de données et les tables

**Ce qu'il contient :**
1. Création de la base `appinfo`
2. Table `users` (utilisateurs)
3. Table `vehicles` (voitures)
4. Un utilisateur de test (`admin@test.com`)
5. 5 voitures de test (Porsche, Tesla, Toyota, Ferrari, BMW)

**Exécution :** Automatique au démarrage de Docker

---

## 🐳 FICHIERS DOCKER

### docker-compose.yml
**Rôle :** Configure les 3 conteneurs Docker

**Les 3 services :**
1. **www** - Serveur web Apache + PHP (port 8080)
2. **db** - Serveur MySQL (port 3306)
3. **phpmyadmin** - Interface web pour MySQL (port 8001)

---

### Dockerfile
**Rôle :** Crée l'image Docker pour le serveur web

**Ce qu'il installe :**
- PHP 8.2 avec Apache
- Extensions PDO et MySQL
- Xdebug (pour le débogage)

---

## 📚 FICHIERS DE DOCUMENTATION

### GUIDE_DEBUTANT.md
**Rôle :** Guide complet pour apprendre le projet

**Contenu :**
- Explication de toutes les technologies
- Comment fonctionne Docker
- La structure de la base de données
- Explication détaillée de chaque fichier
- Concepts importants (sessions, PDO, sécurité)
- Glossaire des termes techniques

**👉 À LIRE EN PRIORITÉ si vous débutez !**

---

### README.md
**Rôle :** Documentation principale du projet

**Contenu :**
- Fonctionnalités
- Installation
- Utilisation
- Structure du projet
- Commandes Docker

---

### RESUME_PROJET.md
**Rôle :** Ce fichier ! Un résumé ultra-rapide

---

## 🔑 CONCEPTS CLÉS À COMPRENDRE

### 1. Sessions PHP
Les sessions permettent de garder des informations sur un utilisateur pendant sa navigation.

**Exemple :**
```php
$_SESSION['user_id'] = 5;  // On garde l'ID de l'utilisateur connecté
```

---

### 2. PDO (PHP Data Objects)
PDO est la manière sécurisée de communiquer avec MySQL.

**Requête sécurisée :**
```php
$stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = ?');
$stmt->execute([$id]);
```

**Pourquoi les `?` ?** Protection contre les injections SQL !

---

### 3. GET vs POST

**GET** - Dans l'URL (visible)
```
detail.php?id=5
```

**POST** - Dans le corps de la requête (invisible)
```php
$_POST['email']
```

**Utiliser GET pour :** Récupérer des données (affichage)  
**Utiliser POST pour :** Envoyer des données (connexion, ajout, suppression)

---

### 4. Hachage des mots de passe

**Mauvaise pratique :** Stocker le mot de passe en clair
```
mot_de_passe = "admin123"  ❌ DANGER !
```

**Bonne pratique :** Hacher le mot de passe
```php
password_hash("admin123", PASSWORD_DEFAULT)
// Résultat : "$2y$10$mDLuz/7.cZ..." ✅ SÉCURISÉ
```

**Vérification :**
```php
password_verify("admin123", $hash)  // true ou false
```

---

## 🎯 FLUX D'UTILISATION

### Scénario 1 : Voir le catalogue (visiteur non connecté)
1. Visite http://localhost:8080/
2. `index.php` récupère toutes les voitures
3. Affiche la liste
4. Clic sur "Voir les détails" → `detail.php?id=1`
5. `detail.php` récupère la voiture avec l'ID 1
6. Affiche ses informations

---

### Scénario 2 : Se connecter et ajouter une voiture
1. Clic sur "Connexion" → `login.php`
2. Entre email + mot de passe
3. `login.php` vérifie dans MySQL
4. Si OK → crée une session et redirige vers `index.php`
5. Clic sur "➕ Ajouter une voiture" → `add.php`
6. Remplit le formulaire
7. `add.php` insère dans MySQL
8. Redirige vers `detail.php?id=6` (la nouvelle voiture)

---

### Scénario 3 : Comparer des voitures
1. Sur `index.php`, coche 3 voitures
2. JavaScript affiche le bouton "3 voiture(s) à comparer"
3. Clic sur le bouton → `compare.php?ids=1,2,3`
4. `compare.php` récupère les voitures 1, 2 et 3
5. Affiche un tableau comparatif

---

## 🛡️ SÉCURITÉ

| Menace | Protection |
|--------|-----------|
| Injection SQL | Requêtes préparées PDO |
| Mots de passe volés | Hachage avec `password_hash()` |
| Attaque XSS | `escape()` / `htmlspecialchars()` |
| Accès non autorisé | `requireLogin()` |

---

## ✅ CHECKLIST DE COMPRÉHENSION

Cochez ce que vous avez compris :

- [ ] Je sais à quoi sert `config.php`
- [ ] Je comprends comment fonctionne `index.php`
- [ ] Je sais comment on se connecte avec `login.php`
- [ ] Je comprends la différence entre GET et POST
- [ ] Je sais ce qu'est une session PHP
- [ ] Je comprends pourquoi on hash les mots de passe
- [ ] Je sais ce qu'est PDO et pourquoi c'est sécurisé
- [ ] Je comprends comment fonctionne la comparaison
- [ ] Je sais à quoi servent les 3 conteneurs Docker
- [ ] Je peux expliquer le flux complet d'ajout d'une voiture

---

## 🚀 PROCHAINES ÉTAPES

1. **Testez le projet** - Démarrez Docker et explorez toutes les fonctionnalités
2. **Lisez les commentaires** - Tous les fichiers PHP sont commentés en détail
3. **Consultez GUIDE_DEBUTANT.md** - Pour approfondir chaque concept
4. **Modifiez le code** - Essayez d'ajouter une nouvelle fonctionnalité !

---

**Bon apprentissage ! 🎓**
