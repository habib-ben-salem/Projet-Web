# 🚗 Mini Catalogue de Voitures

Projet universitaire simple pour débutants : un mini catalogue de voitures avec authentification.

## 📋 Stack Technique

- **Frontend** : HTML + Bootstrap 5
- **Backend** : PHP 8.2
- **Base de données** : MySQL
- **Conteneurisation** : Docker (PHP + MySQL + phpMyAdmin)

## 🎯 Fonctionnalités

### Accès Public
- ✅ Afficher la liste des voitures
- ✅ Voir le détail d'une voiture

### Accès Authentifié
- ✅ Ajouter une voiture
- ✅ Supprimer une voiture
- ✅ Authentification simple avec sessions PHP

## 🗄️ Structure de la Base de Données

### Table `users`
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- email (VARCHAR(255), UNIQUE)
- password_hash (VARCHAR(255))
- created_at (TIMESTAMP)
```

### Table `vehicles`
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

## 📁 Structure des Fichiers

```
dev-web-mobile/
├── docker-compose.yml          # Configuration Docker
├── Dockerfile                  # Image PHP avec extensions PDO
├── README.md                   # Ce fichier
├── db/
│   ├── database.sql           # Script de création de la BDD
│   └── users.sql              # (Non utilisé)
└── src/                       # Code source PHP
    ├── config.php             # Configuration BDD + helpers
    ├── index.php              # Liste des voitures
    ├── detail.php             # Détail d'une voiture
    ├── add.php                # Ajouter une voiture (authentifié)
    ├── delete.php             # Supprimer une voiture (authentifié)
    ├── login.php              # Page de connexion
    └── logout.php             # Déconnexion
```

## 🚀 Installation et Démarrage

### Prérequis
- Docker Desktop installé et démarré

### Étapes

1. **Cloner ou télécharger le projet**
   ```bash
   cd dev-web-mobile
   ```

2. **Lancer les conteneurs Docker**
   ```bash
   docker-compose up -d
   ```
   
   Cette commande va :
   - Construire l'image PHP avec les extensions PDO/MySQL
   - Démarrer le serveur web Apache sur le port 8080
   - Démarrer MySQL sur le port 3306
   - Démarrer phpMyAdmin sur le port 8001
   - Créer automatiquement la base de données et les tables

3. **Accéder à l'application**
   - **Site web** : http://localhost:8080
   - **phpMyAdmin** : http://localhost:8001
     - Serveur : `db`
     - Utilisateur : `php_docker`
     - Mot de passe : `password`

## 👤 Compte de Test

Un utilisateur de test est créé automatiquement :

- **Email** : `admin@test.com`
- **Mot de passe** : `admin123`

> Le mot de passe est haché avec `password_hash()` dans la base de données.

## 🛠️ Utilisation

### Navigation Public
1. Visitez http://localhost:8080
2. Parcourez le catalogue des voitures
3. Cliquez sur "Voir les détails" pour accéder aux informations complètes

### Ajout/Suppression de Voitures
1. Cliquez sur "Connexion" dans le menu
2. Utilisez le compte de test ci-dessus
3. Une fois connecté :
   - Cliquez sur "➕ Ajouter une voiture" pour ajouter un véhicule
   - Sur la page de détail, cliquez sur "🗑️ Supprimer" pour supprimer

## 🔐 Sécurité Implémentée

- ✅ **PDO avec requêtes préparées** : Protection contre les injections SQL
- ✅ **password_hash() / password_verify()** : Hashage sécurisé des mots de passe
- ✅ **Sessions PHP** : Gestion de l'authentification
- ✅ **htmlspecialchars()** : Protection XSS (escape())
- ✅ **Validation des données** : Vérification des inputs côté serveur

## 📝 Exemple CRUD

| Opération | Fichier | Authentification |
|-----------|---------|------------------|
| **C**reate | `add.php` | ✅ Requis |
| **R**ead (Liste) | `index.php` | ❌ Public |
| **R**ead (Détail) | `detail.php` | ❌ Public |
| **U**pdate | *(Non implémenté)* | - |
| **D**elete | `delete.php` | ✅ Requis |

## 🛑 Arrêter le Projet

```bash
docker-compose down
```

Pour supprimer également les volumes (base de données) :
```bash
docker-compose down -v
```

## 📚 Technologies Utilisées

- **PHP 8.2** avec extensions : PDO, pdo_mysql, mysqli, xdebug
- **MySQL latest**
- **Bootstrap 5.3** (CDN)
- **Apache** (inclus dans l'image php:8.2-apache)
- **Docker & Docker Compose**

## 🎓 Points Pédagogiques

Ce projet illustre :
- Architecture MVC simple (sans framework)
- Connexion PDO à MySQL
- Sécurité de base (sessions, hachage, requêtes préparées)
- CRUD minimal
- Utilisation de Docker pour le développement
- Bootstrap pour un design responsive

---

**Projet Universitaire 2026** - Mini Catalogue de Voitures

