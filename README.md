# 🚗 Catalogue de Voitures - Projet Universitaire

Un site web de gestion de catalogue de voitures créé avec **PHP**, **MySQL**, **Bootstrap** et **Docker**.

---

## ✨ Fonctionnalités

### Pour tous les visiteurs :
- 📋 Voir le catalogue complet des voitures
- 🔍 Voir les détails d'une voiture
- ⚖️ **Comparer plusieurs voitures côte à côte** (NOUVEAU !)

### Pour les utilisateurs connectés :
- 🔐 Se connecter avec un compte
- ➕ Ajouter des voitures au catalogue
- 🗑️ Supprimer des voitures
- 🚪 Se déconnecter

---

## 🛠️ Technologies utilisées

| Technologie | Description |
|------------|-------------|
| **PHP 8.x** | Langage backend pour la logique métier |
| **MySQL 8.x** | Base de données relationnelle |
| **PDO** | Interface sécurisée pour MySQL (protection contre injections SQL) |
| **Docker** | Conteneurisation de l'application |
| **Bootstrap 5** | Framework CSS pour le design responsive |
| **JavaScript** | Interactivité côté client (comparaison de voitures) |

---

## 🚀 Installation et démarrage

### Prérequis
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installé et en cours d'exécution
- Un navigateur web (Chrome, Firefox, Edge...)

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   cd dev-web-mobile
   ```

2. **Démarrer Docker Desktop**
   - Ouvrez Docker Desktop et attendez qu'il soit prêt

3. **Lancer les conteneurs**
   ```bash
   docker-compose up -d
   ```
   
   Cette commande va :
   - Créer 3 conteneurs (serveur web, MySQL, phpMyAdmin)
   - Installer toutes les dépendances
   - Créer la base de données avec des données de test

4. **Attendre le démarrage**
   - Attendez environ 30 secondes que MySQL s'initialise

5. **Accéder au site**
   - Ouvrez votre navigateur
   - Allez sur : **http://localhost:8080/**

---

## 🌐 URLs importantes

| Service | URL | Description |
|---------|-----|-------------|
| **Site web** | http://localhost:8080 | Page d'accueil du catalogue |
| **phpMyAdmin** | http://localhost:8001 | Interface pour gérer la base de données |

### Connexion à phpMyAdmin
- **Serveur :** `db`
- **Utilisateur :** `php_docker`
- **Mot de passe :** `password`

---

## 👤 Compte de test

Un compte administrateur est créé automatiquement :

- **Email :** `admin@test.com`
- **Mot de passe :** `admin123`

Utilisez ce compte pour tester les fonctionnalités d'ajout et de suppression.

---

## 📁 Structure du projet

```
dev-web-mobile/
│
├── docker-compose.yml       # Configuration Docker (3 services)
├── Dockerfile               # Image pour le serveur web PHP
│
├── db/                      # Scripts SQL
│   ├── database.sql         # Création des tables + données de test
│   └── users.sql            # (optionnel)
│
├── src/                     # Code source PHP
│   ├── config.php           # ⭐ Fichier de configuration (fonctions utiles)
│   ├── index.php            # Page d'accueil (liste des voitures)
│   ├── login.php            # Page de connexion
│   ├── logout.php           # Déconnexion
│   ├── add.php              # Ajout d'une voiture
│   ├── detail.php           # Détails d'une voiture
│   ├── delete.php           # Suppression d'une voiture
│   └── compare.php          # ⭐ Comparaison de voitures (NOUVEAU)
│
├── GUIDE_DEBUTANT.md        # 📚 Guide complet pour apprendre le projet
└── README.md                # Ce fichier
```

**Note :** Tous les fichiers PHP sont **commentés en détail** pour faciliter l'apprentissage !

---

## 📚 Documentation pour débutants

### 🎓 Vous êtes débutant en développement web ?

Consultez le fichier **[GUIDE_DEBUTANT.md](GUIDE_DEBUTANT.md)** qui explique :

- 📖 Comment fonctionne le projet (architecture globale)
- 🧩 Chaque technologie utilisée (PHP, MySQL, Docker, etc.)
- 🔍 Ligne par ligne, ce que fait chaque fichier
- 🛡️ Les concepts de sécurité (sessions, PDO, hachage...)
- 🎓 Un glossaire de tous les termes techniques
- ✅ Une checklist de compréhension

**Tous les fichiers PHP contiennent également des commentaires détaillés** qui expliquent chaque ligne de code.

---

## 🆕 Nouvelle fonctionnalité : Comparaison de voitures

### Comment utiliser ?

1. **Sur la page d'accueil**, cochez les voitures que vous voulez comparer (cases en haut à droite de chaque carte)

2. **Un bouton vert apparaît en bas à droite** indiquant le nombre de voitures sélectionnées

3. **Cliquez sur ce bouton** pour voir le tableau comparatif

4. **Le tableau affiche** côte à côte :
   - Images
   - Marques et modèles
   - Années
   - Prix
   - Descriptions

---

## 🗄️ Base de données

### Tables créées automatiquement

**Table `users`** - Comptes utilisateurs
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- email (VARCHAR(255), UNIQUE)
- password_hash (VARCHAR(255))
- created_at (TIMESTAMP)
```

**Table `vehicles`** - Voitures du catalogue
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- brand (VARCHAR(100))
- model (VARCHAR(100))
- year (INT)
- price (DECIMAL(10,2))
- image_path (VARCHAR(255), NULLABLE)
- description (TEXT, NULLABLE)
- created_at (TIMESTAMP)
```

### Données de test

Le projet inclut **5 voitures de test** :
1. Porsche 911 GT3
2. Tesla Model S Plaid
3. Toyota Prius
4. Ferrari F8 Tributo
5. BMW M3 Competition

---

## ⚙️ Commandes Docker utiles

```bash
# Démarrer tous les conteneurs
docker-compose up -d

# Arrêter tous les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Redémarrer (après avoir modifié docker-compose.yml)
docker-compose restart

# Reconstruire les images (après avoir modifié Dockerfile)
docker-compose up -d --build
```

---

## 🔧 Développement

### Modifier le code PHP
- Les fichiers dans `src/` sont synchronisés avec le conteneur
- Modifiez les fichiers et rafraîchissez simplement le navigateur
- Pas besoin de redémarrer Docker !

### Modifier les données
- Utilisez phpMyAdmin : http://localhost:8001
- Ou modifiez `db/database.sql` et recréez les conteneurs

---

## 🛡️ Sécurité

Ce projet implémente plusieurs bonnes pratiques de sécurité :

✅ **Requêtes préparées PDO** - Protection contre les injections SQL  
✅ **Hachage bcrypt** - Les mots de passe ne sont jamais stockés en clair  
✅ **Échappement HTML** - Protection contre les attaques XSS  
✅ **Validation des données** - Vérification côté serveur  
✅ **Sessions sécurisées** - Gestion des utilisateurs connectés  
✅ **Contrôle d'accès** - Certaines pages nécessitent une connexion

---

## 📝 Notes importantes

- Ce projet est à **but pédagogique**
- Pour un site en production, il faudrait ajouter :
  - HTTPS (SSL/TLS)
  - Gestion d'erreurs plus robuste
  - Upload d'images (actuellement URLs)
  - Pagination du catalogue
  - Recherche et filtres
  - Tests unitaires

---

## 🎓 Apprentissage

Ce projet est parfait pour apprendre :
- Les bases de **PHP**
- L'utilisation de **MySQL** avec **PDO**
- La **conteneurisation** avec **Docker**
- Les concepts de **sessions** et **authentification**
- Le **design responsive** avec **Bootstrap**
- Les interactions **JavaScript** de base

**Consultez [GUIDE_DEBUTANT.md](GUIDE_DEBUTANT.md) pour une explication complète !**

---
