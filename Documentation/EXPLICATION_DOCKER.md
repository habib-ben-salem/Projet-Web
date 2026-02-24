# 🐳 Comment fonctionne Docker dans ce projet ?

Ce guide explique **en détail** comment Docker permet à votre projet de fonctionner.

---

## 🎯 Table des matières

1. [Les 3 containers du projet](#les-3-containers-du-projet)
2. [La notation des ports (8080:80)](#la-notation-des-ports-808080)
3. [Comment fonctionne la connexion PDO](#comment-fonctionne-la-connexion-pdo)
4. [Le réseau Docker invisible](#le-réseau-docker-invisible)
5. [Visualisation complète](#visualisation-complète)

---

## 📦 Les 3 containers du projet

Votre `docker-compose.yml` crée **3 containers** (comme 3 petits ordinateurs virtuels) :

```yaml
services:
  www:          # Container 1 : Serveur Web
  db:           # Container 2 : MySQL
  phpmyadmin:   # Container 3 : Interface Web pour MySQL
```

### 🔍 Détail de chaque container :

| Container | Contient | Port exposé | Rôle |
|-----------|----------|-------------|------|
| **www** | Apache + PHP | `8080:80` | Exécute votre code PHP |
| **db** | MySQL | `3306:3306` | Stocke les données |
| **phpmyadmin** | phpMyAdmin | `8001:80` | Interface pour gérer MySQL |

---

## 🔌 La notation des ports : `8080:80`

### ❓ Qu'est-ce que ça veut dire ?

```yaml
ports:
  - "8080:80"
     ↑     ↑
     │     └─ Port INTERNE (dans le container)
     └─────── Port EXTERNE (sur votre ordinateur)
```

### 📖 Format complet :

```
EXTERNE:INTERNE
```

- **Port EXTERNE** : Le port que **VOUS** utilisez sur **votre ordinateur**
- **Port INTERNE** : Le port utilisé **À L'INTÉRIEUR** du container

### 🎯 Exemple concret avec `8080:80` :

```
┌─────────────────────────────────────────────────┐
│ Votre ordinateur (Windows)                      │
│                                                 │
│  Vous tapez: http://localhost:8080              │
│                              ↓                  │
│                         Port 8080               │
└────────────────────────┬────────────────────────┘
                         │
                         │ Docker fait la redirection
                         ↓
┌─────────────────────────────────────────────────┐
│ Container "www"                                 │
│                                                 │
│  Apache écoute sur le port 80 (standard)        │
│                              ↑                  │
│                         Port 80                 │
└─────────────────────────────────────────────────┘
```

### 💡 Pourquoi deux ports différents ?

**Raison 1 : Éviter les conflits**
- Votre ordinateur a peut-être déjà un programme sur le port 80
- Windows, d'autres applications peuvent utiliser le port 80
- En utilisant 8080 sur votre PC, on évite les conflits

**Raison 2 : Flexibilité**
- À l'intérieur du container, Apache utilise **toujours** le port 80 (standard)
- Mais VOUS pouvez choisir N'IMPORTE QUEL port externe

**Exemples possibles :**
```yaml
ports:
  - "8080:80"   # ✅ Ce qu'on utilise
  - "3000:80"   # ✅ Marchera aussi ! (http://localhost:3000)
  - "9999:80"   # ✅ Marchera aussi ! (http://localhost:9999)
  - "80:80"     # ⚠️ Peut ne pas marcher (port 80 déjà pris sur Windows)
```

### 📚 Tous les ports du projet :

```yaml
# Container WWW (serveur web)
www:
  ports:
    - "8080:80"
    #   ↑    ↑
    #   │    └─ Apache écoute sur le port 80 dans le container
    #   └────── Vous accédez via localhost:8080

# Container DB (MySQL)
db:
  ports:
    - "3306:3306"
    #   ↑     ↑
    #   │     └─ MySQL écoute sur le port 3306 (standard MySQL)
    #   └─────── Vous pouvez vous connecter avec un client MySQL sur port 3306

# Container phpMyAdmin
phpmyadmin:
  ports:
    - "8001:80"
    #   ↑    ↑
    #   │    └─ phpMyAdmin (serveur web) écoute sur port 80
    #   └────── Vous accédez via localhost:8001
```

### 🎭 Changement de ports - Exemple

Si vous voulez utiliser d'autres ports, modifiez juste la partie EXTERNE :

```yaml
# AVANT
ports:
  - "8080:80"    # Site sur localhost:8080
  - "8001:80"    # phpMyAdmin sur localhost:8001

# APRÈS
ports:
  - "9000:80"    # Site sur localhost:9000
  - "9001:80"    # phpMyAdmin sur localhost:9001
```

**Important :** Ne changez JAMAIS la partie INTERNE (après les `:`) sauf si vous savez ce que vous faites !

---

## 🔐 Comment fonctionne la connexion PDO ?

### 📝 Dans votre code PHP (`config.php`) :

```php
$pdo = new PDO(
    "mysql:host=db;dbname=appinfo",
    "php_docker",
    "password"
);
```

### 🤔 Décortiquons chaque élément :

#### 1️⃣ `host=db` - Le nom du container MySQL

**Ce qui se passe en coulisses :**

```
Container PHP dit : "Je veux parler à 'db'"
         ↓
Docker DNS traduit "db" → 172.18.0.3 (adresse IP interne)
         ↓
Container PHP se connecte à 172.18.0.3:3306
         ↓
Container MySQL reçoit la connexion !
```

**Pourquoi "db" et pas "localhost" ?**
- `localhost` dans le container PHP = le container PHP lui-même ❌
- `db` = le container MySQL (grâce au réseau Docker) ✅

**D'où vient le nom "db" ?**
```yaml
# Dans docker-compose.yml
services:
  db:        # ← Ce nom devient le hostname dans le réseau Docker !
    image: mysql:latest
```

#### 2️⃣ `php_docker` - L'utilisateur MySQL

**Création automatique au démarrage :**
```yaml
# Dans docker-compose.yml
db:
  environment:
    MYSQL_USER: php_docker      # ← Crée cet utilisateur
    MYSQL_PASSWORD: password    # ← Avec ce mot de passe
    MYSQL_DATABASE: appinfo     # ← Sur cette base de données
```

**Ce qui se passe au premier démarrage du container MySQL :**
```bash
# MySQL exécute automatiquement :
CREATE USER 'php_docker'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON appinfo.* TO 'php_docker'@'%';
CREATE DATABASE appinfo;
```

Le `@'%'` signifie : "Peut se connecter depuis n'importe où dans le réseau Docker"

#### 3️⃣ `dbname=appinfo` - La base de données

```yaml
db:
  environment:
    MYSQL_DATABASE: appinfo    # ← Base de données créée automatiquement
  volumes:
    - "./db:/docker-entrypoint-initdb.d"
```

**Séquence de démarrage :**
1. Container MySQL démarre
2. MySQL crée la base `appinfo`
3. MySQL cherche des fichiers `.sql` dans `/docker-entrypoint-initdb.d`
4. MySQL exécute `database.sql` (crée les tables `users` et `vehicles`)

---

## 🌐 Le réseau Docker invisible

Docker crée automatiquement un **réseau privé virtuel** pour vos containers.

### 📡 Schéma du réseau :

```
┌───────────────────────────────────────────────────────────┐
│ Votre ordinateur (Windows) - 192.168.1.100               │
│                                                           │
│  Vous tapez: http://localhost:8080                       │
│                                                           │
│  ┌─────────────────────────────────────────────────────┐ │
│  │ Réseau Docker (172.18.0.0/16) - PRIVÉ              │ │
│  │                                                     │ │
│  │  ┌──────────────────┐    ┌──────────────────┐     │ │
│  │  │ Container "www"  │    │ Container "db"   │     │ │
│  │  │ IP: 172.18.0.2   │───▶│ IP: 172.18.0.3   │     │ │
│  │  │ Port interne: 80 │    │ Port interne:3306│     │ │
│  │  │ Port exposé: 8080│    │ Port exposé: 3306│     │ │
│  │  └──────────────────┘    └──────────────────┘     │ │
│  │           │                                        │ │
│  │           │  ┌──────────────────┐                 │ │
│  │           └──│ Container        │                 │ │
│  │              │ "phpmyadmin"     │                 │ │
│  │              │ IP: 172.18.0.4   │                 │ │
│  │              │ Port interne: 80 │                 │ │
│  │              │ Port exposé: 8001│                 │ │
│  │              └──────────────────┘                 │ │
│  │                                                     │ │
│  └─────────────────────────────────────────────────────┘ │
│                                                           │
└───────────────────────────────────────────────────────────┘
```

### 🔍 Communication entre containers :

```php
// Dans le container PHP, on peut utiliser directement le NOM du container
$pdo = new PDO("mysql:host=db", ...);
                           ↑
                           └─ Docker traduit "db" en "172.18.0.3"
```

**Les containers peuvent se parler :**
- ✅ `www` peut parler à `db` (via le nom "db")
- ✅ `www` peut parler à `phpmyadmin` (via le nom "phpmyadmin")
- ✅ `phpmyadmin` peut parler à `db` (via le nom "db")

**Mais l'extérieur ne peut PAS accéder directement :**
- ❌ Internet ne peut pas accéder au port 3306 de MySQL
- ❌ Votre réseau local ne peut pas accéder directement au port 80 du container
- ✅ Seuls les ports **exposés** dans `ports:` sont accessibles depuis votre ordinateur

---

## 🎬 Visualisation complète : Que se passe-t-il quand vous chargez une page ?

### Étape par étape :

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Vous tapez: http://localhost:8080/index.php             │
│    dans votre navigateur Chrome/Firefox                     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Windows redirige le port 8080 vers le container "www"   │
│    (grâce à Docker qui fait le mapping 8080:80)             │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Apache (dans container "www") reçoit la requête HTTP    │
│    sur son port 80 interne                                  │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Apache lance PHP pour exécuter index.php                │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. PHP exécute: require_once 'config.php'                  │
│    Puis: $pdo = getDbConnection()                          │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. PHP crée une connexion PDO:                             │
│    new PDO("mysql:host=db;dbname=appinfo", ...)            │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. Docker DNS traduit "db" en adresse IP 172.18.0.3        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 8. PDO se connecte au port 3306 du container "db"          │
│    (connexion TCP sur 172.18.0.3:3306)                     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 9. MySQL vérifie les credentials:                          │
│    Utilisateur: php_docker                                  │
│    Mot de passe: password                                   │
│    Base de données: appinfo                                 │
│    → ✅ Authentification réussie !                          │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 10. PHP exécute: SELECT * FROM vehicles                    │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 11. MySQL cherche dans la table "vehicles"                 │
│     et retourne les 5 voitures                              │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 12. PHP reçoit les données et génère le HTML:              │
│     <div class="card">                                      │
│       <h5>Porsche 911 GT3</h5>                             │
│       <p class="price">196 500,00 €</p>                    │
│     </div>                                                  │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 13. Apache renvoie le HTML au port 80                      │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 14. Docker redirige du port 80 (container) au 8080 (PC)    │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────┐
│ 15. Votre navigateur reçoit le HTML et l'affiche ! 🎉      │
└─────────────────────────────────────────────────────────────┘
```

**Temps total : Environ 50-200 millisecondes** ⚡

---

## 🔐 Sécurité : Pourquoi c'est isolé ?

```
┌─────────────────────────────────────────────────────┐
│ INTERNET (monde extérieur)                          │
│                                                     │
│        ❌ Ne peut PAS accéder directement           │
│           au port 3306 de MySQL                     │
│                                                     │
└────────────────────┬────────────────────────────────┘
                     │
                     │ Seuls les ports exposés sont accessibles
                     │
                     ↓
┌─────────────────────────────────────────────────────┐
│ Votre ordinateur (localhost)                        │
│                                                     │
│  ✅ Accès au port 8080 (site web)                   │
│  ✅ Accès au port 8001 (phpMyAdmin)                 │
│  ✅ Accès au port 3306 (MySQL) MAIS uniquement      │
│     depuis localhost, pas depuis internet           │
│                                                     │
│  ┌───────────────────────────────────────────────┐ │
│  │ Réseau Docker (PRIVÉ)                         │ │
│  │                                               │ │
│  │  Les containers communiquent entre eux        │ │
│  │  mais sont isolés du monde extérieur          │ │
│  │                                               │ │
│  │  🔒 Port 3306 de MySQL accessible SEULEMENT  │ │
│  │     par les autres containers du réseau       │ │
│  └───────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────┘
```

### 🛡️ Protection par couches :

1. **Réseau Docker privé** : Les containers sont dans leur propre réseau
2. **Ports sélectifs** : Seuls les ports que vous exposez sont accessibles
3. **Utilisateur MySQL limité** : `php_docker` a accès seulement à `appinfo`
4. **PDO avec requêtes préparées** : Protection contre les injections SQL

---

## 📋 Tableau récapitulatif

| Accès | URL / Commande | Port PC | Port Container | Container | Service |
|-------|---------------|---------|----------------|-----------|---------|
| **Site web** | http://localhost:8080 | 8080 | 80 | www | Apache + PHP |
| **phpMyAdmin** | http://localhost:8001 | 8001 | 80 | phpmyadmin | Interface MySQL |
| **MySQL direct** | mysql -h localhost -P 3306 | 3306 | 3306 | db | MySQL |
| **Réseau interne** | host=db (dans PHP) | - | 3306 | db | MySQL |

---

## ❓ Questions fréquentes

### Q1 : Comment le serveur web (www) et phpMyAdmin peuvent-ils utiliser le même port 80 ?
**R :** Ils **ne sont PAS sur le même port** ! C'est une confusion courante.

**Sur votre PC (ports externes) :**
- Site web → Port **8080**
- phpMyAdmin → Port **8001**
- **Deux ports DIFFÉRENTS !**

**Dans Docker (ports internes) :**
- Container `www` a **son propre** port 80
- Container `phpmyadmin` a **son propre** port 80
- **Pas de conflit car ce sont des containers ISOLÉS !**

**Analogie :** C'est comme un immeuble où chaque appartement (container) peut avoir une porte numéro 80, car ils sont dans des espaces séparés.

```
localhost:8080 → Port 80 du container "www"
localhost:8001 → Port 80 du container "phpmyadmin"
```

Chaque container est un **mini-ordinateur virtuel indépendant** avec ses propres ports !

### Q2 : Puis-je changer le port 8080 en 3000 ?
**R :** Oui ! Modifiez simplement :
```yaml
ports:
  - "3000:80"   # Au lieu de "8080:80"
```
Puis redémarrez : `docker-compose down && docker-compose up -d`

### Q3 : Pourquoi "host=db" et pas "localhost" dans PHP ?
**R :** Parce que `localhost` dans le container PHP = le container PHP lui-même (pas MySQL). `db` est le nom du service MySQL dans docker-compose.yml, Docker le traduit automatiquement en adresse IP.

### Q3 : Est-ce que quelqu'un sur internet peut accéder à ma base de données ?
**R :** Non ! Les ports sont exposés seulement sur `localhost` (127.0.0.1). Personne sur internet ne peut y accéder. Pour qu'internet y accède, il faudrait configurer votre routeur (port forwarding) - ce qui serait dangereux !

### Q4 : Que se passe-t-il si j'ai déjà un serveur sur le port 8080 ?
**R :** Docker vous donnera une erreur. Changez le port externe :
```yaml
ports:
  - "9000:80"   # Utilisez un autre port libre
```

### Q5 : Où sont stockées les données MySQL ?
**R :** Dans un **volume Docker**. Même si vous arrêtez les containers, les données restent. Pour tout effacer : `docker-compose down -v`

---

## 🎓 Conclusion

**Docker fait toute la "magie" en coulisses :**

1. **Crée un réseau privé** où vos containers peuvent communiquer
2. **Traduit les noms** (comme "db") en adresses IP
3. **Redirige les ports** (8080 sur votre PC → 80 dans le container)
4. **Isole tout** pour la sécurité
5. **Configure MySQL** automatiquement au démarrage

**Vous n'avez qu'à :**
- Lancer `docker-compose up -d`
- Accéder à `localhost:8080`
- Coder tranquillement ! 🚀

Tout le reste est géré automatiquement par Docker ! 🐳
