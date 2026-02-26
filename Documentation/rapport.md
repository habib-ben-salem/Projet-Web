# Rapport de Projet Web — Catalogue Automobile en Ligne

---

**Projet Universitaire — Programmation Web & Application Mobile (App3)**

**Membres de l'équipe :**
- **HB** — *Habib BENSALAH*
- **MR** — *[Prénom NOM]*

**Nom du projet :** Catalogue Auto — Plateforme de consultation et comparaison de véhicules

**Année universitaire :** 2025–2026

---

## Sommaire

1. [Contexte](#1-contexte)
   - 1.1 Présentation et mise en situation
   - 1.2 Objectifs du projet
   - 1.3 Contexte d'utilisation
2. [Planification](#2-planification)
   - 2.1 Répartition du travail et rôles
   - 2.2 Technologies maîtrisées et technologies abordées pour la première fois
   - 2.3 Cahier des charges
   - 2.4 Calendrier prévisionnel et réel
3. [Description du développement](#3-description-du-développement)
   - 3.1 Technologies web utilisées
   - 3.2 Étapes et tâches menées
   - 3.3 Images du site
   - 3.4 Schéma relationnel de la base de données
   - 3.5 Difficultés rencontrées, erreurs et solutions
   - 3.6 État des lieux de la réalisation — retour au cahier des charges
   - 3.7 Défauts et améliorations à proposer
   - 3.8 Licence du code
   - 3.9 Accessibilité et bonnes pratiques
4. [Réalisation — Hébergement en ligne](#4-réalisation--hébergement-en-ligne)
5. [Conclusion et réflexion](#5-conclusion-et-réflexion)
   - 5.1 État global d'avancement
   - 5.2 Auto-réflexion individuelle — HB
   - 5.3 Auto-réflexion individuelle — MR
   - 5.4 Utilisation de l'intelligence artificielle
6. [Annexes](#6-annexes)
   - Annexe A : Bibliographie et ressources
   - Annexe B : Extraits de code intéressants
   - Annexe C : Configuration Docker complète
   - Annexe D : Scripts SQL de la base de données

---

## 1. Contexte

### 1.1 Présentation et mise en situation

De nos jours, avec le développement de la technologie digitale et d'Internet, l'accès à l'information est de plus en plus facile. Cependant, cette accessibilité est parfois à double tranchant. En effet, la propagation de fausses informations n'a jamais été aussi répandue. La fiabilité et l'authenticité de celles-ci constituent un facteur déterminant, d'autant plus dans les secteurs d'activités de l'industrie B2C (Business to Consumer).

Dans un contexte socio-économique en perpétuelle expansion, où les acteurs sont nombreux et où le manque de connaissances peut s'avérer préjudiciable — comme dans le marché de l'automobile —, il devient essentiel de disposer d'outils fiables et centralisés pour consulter des informations techniques.

Le marché automobile est un domaine particulièrement concurrentiel où les consommateurs font face à une multitude de choix. Les informations techniques (puissance, couple, vitesse maximale, type de motorisation) sont souvent dispersées sur de nombreux sites, ce qui rend difficile une comparaison objective entre différents modèles. Notre projet vise à répondre à ce besoin en proposant une plateforme unique et accessible, regroupant des fiches techniques détaillées et un outil de comparaison visuel.

### 1.2 Objectifs du projet

Les objectifs principaux de ce projet sont les suivants :

- **Créer un catalogue automobile dynamique** permettant de consulter des fiches détaillées de véhicules (marque, modèle, année, prix, caractéristiques techniques).
- **Développer un comparateur de véhicules** offrant une vue côte-à-côte de plusieurs modèles.
- **Mettre en place un système d'authentification sécurisé** (inscription, connexion, déconnexion) permettant aux utilisateurs authentifiés d'enrichir la base de données.
- **Conteneuriser l'application avec Docker** afin de garantir un déploiement reproductible sur n'importe quel environnement.
- **Acquérir des compétences** en développement web full-stack (PHP, MySQL, Bootstrap) et en DevOps (Docker, Git).

### 1.3 Contexte d'utilisation

La plateforme s'adresse à plusieurs types d'utilisateurs :

- **Passionnés d'automobile** souhaitant consulter les fiches techniques de différents modèles et les comparer objectivement.
- **Acheteurs potentiels** recherchant des informations centralisées pour guider leur choix avant un achat.
- **Étudiants et curieux** désireux d'apprendre ou de découvrir les caractéristiques de véhicules variés.

La motivation personnelle de notre équipe est liée à notre passion commune pour l'automobile. En tant qu'alternants, nous avons nous-mêmes été confrontés à la difficulté de trouver des informations techniques fiables et comparables lors de nos recherches de véhicules. Ce constat a renforcé notre envie de concevoir un outil répondant concrètement à ce besoin.

---

## 2. Planification

### 2.1 Répartition du travail et rôles

Le tableau suivant résume la répartition des rôles et responsabilités au sein de l'équipe :

| Membre | Rôle principal | Responsabilités détaillées |
|--------|----------------|----------------------------|
| **HB** | Architecture du site & DevOps | Conception de l'architecture globale du site ; préparation des squelettes des différentes pages et de leurs dépendances ; configuration Docker (Dockerfile, docker-compose.yml) ; pages : index (catalogue), login/logout, comparateur, page de détail ; documentation technique (guides Markdown) |
| **MR** | Backend & Base de données | Conception et création de la base de données MySQL ; interactions backend avec la BDD (requêtes PDO) ; module de création de compte utilisateur (formulaire, validation AJAX, insertion BDD) ; gestion de la sécurité des données (hachage des mots de passe, requêtes préparées) |

Au début du projet, le principe adopté était de se concentrer sur l'architecture du site et surtout sur le backend, en déléguant une partie du travail de design à l'aide d'outils d'IA et du framework Bootstrap. HB, évoluant au poste d'ingénieur DevOps en entreprise, a souhaité intégrer Docker au projet afin de créer une application conteneurisée, fonctionnelle sur tous les environnements en local — sachant que la compatibilité entre machines est un problème récurrent dans les phases de test de sites web.

### 2.2 Technologies maîtrisées et technologies abordées pour la première fois

Nos connaissances en technologies web étaient quasi nulles au départ : l'apprentissage s'est fait presque intégralement de manière autonome, en complément du cours. Ce qui caractérise ce projet, c'est que nous sommes partis de ce que nous avons appris en classe, sans aucune expérience préalable en frameworks web.

**HB :**

| Technologies déjà maîtrisées | Technologies abordées pour la 1ère fois |
|-------------------------------|------------------------------------------|
| HTML/CSS (notions de base) | **Bootstrap 5** — framework CSS responsive |
| Connaissances DevOps (contexte entreprise) | **PHP 8.2** — langage backend côté serveur |
| Notions de ligne de commande | **Docker & Docker Compose** — conteneurisation |
| | **YAML** — langage de configuration Docker |
| | **Sécurité web** — sessions, hachage bcrypt, protection XSS |

**MR :**

| Technologies déjà maîtrisées | Technologies abordées pour la 1ère fois |
|-------------------------------|------------------------------------------|
| Notions de programmation | **PHP 8.2** — langage backend |
| | **MySQL** — base de données relationnelle |
| | **PDO** — interface sécurisée PHP/MySQL |
| | **Git** — versioning et collaboration |
| | **JavaScript (AJAX/Fetch API)** — requêtes asynchrones |

En termes de frameworks, nous sommes partis de zéro. L'apprentissage s'est fait via des vidéos en ligne, la documentation officielle et l'aide de chatbots IA. Nous avons abordé Bootstrap pour le frontend, PHP pour le backend, et Docker pour l'infrastructure — trois technologies totalement nouvelles pour nous. Nous avons aussi appris comment établir une connexion sécurisée avec la base de données en utilisant PDO et les requêtes préparées.

### 2.3 Cahier des charges

#### Cahier des charges fonctionnel

Le cahier des charges a été élaboré en début de projet. Les fonctionnalités cibles étaient les suivantes :

**Fonctionnalités publiques (sans authentification) :**

1. **Page d'accueil / Catalogue** — Affichage de l'ensemble des véhicules sous forme de cartes (grille responsive) avec image, marque, modèle, année et prix.
2. **Page de détail** — Consultation de la fiche complète d'un véhicule : caractéristiques générales et performances techniques (moteur, puissance, couple, vitesse max, 0-100 km/h).
3. **Comparateur de véhicules** — Sélection de plusieurs véhicules depuis le catalogue et affichage d'un tableau comparatif côte-à-côte.

**Fonctionnalités authentifiées (utilisateurs connectés) :**

4. **Système de connexion / déconnexion** — Formulaire de login sécurisé avec gestion de sessions PHP.
5. **Création de compte** — Formulaire d'inscription avec validation en temps réel (format email, longueur mot de passe, confirmation) et soumission AJAX.
6. **Ajout de véhicules** — Formulaire permettant d'enrichir le catalogue avec de nouveaux véhicules (champs obligatoires et optionnels).
7. **Suppression de véhicules** — Possibilité de retirer un véhicule du catalogue, avec modal de confirmation.

#### Cahier des charges technique

| Composant | Technologie choisie | Justification |
|-----------|---------------------|---------------|
| Backend | PHP 8.2 | Langage serveur robuste, bien documenté, intégration native MySQL |
| Base de données | MySQL 8 | SGBD relationnel populaire, gratuit, performant |
| Frontend | Bootstrap 5.3 + HTML5/CSS3 | Framework responsive recommandé par le cours |
| Conteneurisation | Docker + Docker Compose | Déploiement reproductible, séparation des services |
| Sécurité | PDO (prepared statements), bcrypt, htmlspecialchars | Protection contre injections SQL, mots de passe non stockés en clair, protection XSS |
| Versioning | Git + GitLab | Gestion de versions et collaboration |

#### Architecture Docker cible

L'architecture technique repose sur trois conteneurs Docker interconnectés :

- **Conteneur `www`** — Serveur web Apache + PHP 8.2 (port 8080)
- **Conteneur `db`** — Serveur MySQL 8 (port 3306)
- **Conteneur `phpmyadmin`** — Interface d'administration de la BDD (port 8001)

### 2.4 Calendrier prévisionnel et réel

Le tableau ci-dessous présente le calendrier prévisionnel tel qu'envisagé en début de projet, ainsi que le déroulement réel constaté à la fin :

| Phase | Prévisionnel | Réel | Écart / Remarque |
|-------|-------------|------|-------------------|
| **Phase 1 — Apprentissage des technologies** | Semaines 1–2 | Semaines 1–3 | +1 semaine : la compréhension de Docker, de la syntaxe YAML et du fonctionnement de PHP a nécessité plus de temps que prévu |
| **Phase 2 — Setup Docker + structure BDD** | Semaines 3–4 | Semaines 3–5 | +1 semaine : problèmes de networking entre conteneurs, configuration des volumes, compréhension du cycle de vie des conteneurs |
| **Phase 3 — Système d'authentification** | Semaines 5–6 | Semaines 5–6 | Conforme au prévisionnel : login, logout, création de compte réalisés dans les temps |
| **Phase 4 — CRUD véhicules + catalogue** | Semaines 7–8 | Semaines 7–9 | +1 semaine : organisation des fichiers PHP, navigation entre pages, centralisation de la configuration |
| **Phase 5 — Comparateur + finitions** | Semaines 9–10 | Semaines 9–11 | Le comparateur, initialement prévu comme fonctionnalité simple, s'est avéré plus complexe (JavaScript, requêtes dynamiques) |
| **Phase 6 — Documentation + rapport** | Semaine 11 | Semaines 11–12 | Documentation exhaustive rédigée (4 guides Markdown, 1 830+ lignes au total) |

*Légende : le calendrier réel montre un décalage d'environ 2 semaines par rapport au prévisionnel, principalement dû à la courbe d'apprentissage des technologies nouvelles (Docker, PHP, Bootstrap).*

**Éléments liés à l'apprentissage :**

- **MR** : apprentissage des interactions backend/BDD avec PDO, du langage PHP, et de Git (versioning, résolution de conflits).
- **HB** : apprentissage du langage PHP, du framework Bootstrap, de Docker et Docker Compose, de la sécurité web (sessions, hachage de mots de passe, protection XSS).

L'avancement du projet a été suivi via Git (historique des commits sur GitLab), ce qui a permis de garder une trace des contributions de chaque membre.

---

## 3. Description du développement

### 3.1 Technologies web utilisées

#### Bootstrap 5.3

Bootstrap a été choisi car il était recommandé dans le cadre du cours. Ce framework CSS simplifie considérablement le travail de design et de configuration du site. Bien qu'il soit plus limité par rapport à d'autres frameworks plus modernes (comme Tailwind CSS ou Material UI), il constitue une étape importante dans l'apprentissage : il est relativement simple à prendre en main et moins chronophage à mettre en place et à apprendre que d'autres frameworks frontend.

Bootstrap a été utilisé pour :
- Le **système de grilles responsive** (`col-12`, `col-md-6`, `col-lg-4`) assurant l'adaptation mobile/tablette/desktop.
- Les **composants pré-faits** : navbar, cards, modales de confirmation, formulaires, alertes, boutons.
- Le **design uniforme** à travers toutes les pages du site.

#### PHP 8.2

PHP est le langage côté serveur utilisé pour toute la logique métier de l'application. Il offre :
- Une **intégration native avec MySQL** via l'extension PDO.
- Une **gestion de sessions intégrée** pour l'authentification.
- Des **fonctions de sécurité modernes** : `password_hash()` pour le hachage bcrypt, `htmlspecialchars()` pour la protection XSS.
- Un **écosystème mature** avec une documentation abondante.

Chaque page PHP suit un schéma cohérent : inclusion de `config.php`, traitement de la logique métier (requêtes BDD, validation), puis génération du HTML.

#### MySQL 8

MySQL a été choisi comme système de gestion de base de données relationnelle pour sa popularité, sa gratuité et ses performances. Il est idéal pour une structure de données automobile avec des tables clairement définies. Le support de PDO en PHP permet une interaction sécurisée via les requêtes préparées.

#### Docker et Docker Compose

Docker est un outil DevOps incontournable dans l'environnement professionnel moderne. Son intégration dans ce projet représente pour nous un *level up* majeur. Docker garantit que le projet fonctionne de manière identique sur tous les environnements, simplifie le setup (pas besoin d'installer XAMPP ou WAMP), et sépare proprement les services (web, base de données, administration).

Bien que les premières étapes n'aient pas été faciles, cela nous a pris beaucoup de temps pour comprendre le fonctionnement de Docker et le langage YAML. Cependant, HB a été particulièrement motivé par cet apprentissage car : (1) cela lui servira dans sa carrière d'ingénieur DevOps, et (2) c'est un gain majeur pour les futurs projets à déployer.

L'architecture Docker mise en place est la suivante :

- **Image PHP 8.2-Apache** avec les extensions PDO, pdo_mysql, mysqli et Xdebug.
- **MySQL latest** avec initialisation automatique de la base via les scripts SQL montés en volume.
- **phpMyAdmin** pour l'administration visuelle de la base de données.

#### Autres technologies

- **PDO (PHP Data Objects)** : couche d'abstraction pour la BDD, avec requêtes préparées anti-injection SQL.
- **JavaScript (vanilla)** : validation de formulaires côté client, requêtes AJAX (Fetch API) pour la création de compte, gestion dynamique du comparateur (checkboxes, bouton flottant).
- **Git / GitLab** : versioning du code et collaboration entre les deux membres de l'équipe.

### 3.2 Étapes et tâches menées

Le développement a été mené en plusieurs phases, chacune correspondant à un ensemble cohérent de fonctionnalités.

#### Phase 1 — Configuration de l'environnement Docker

La première étape a consisté à mettre en place l'infrastructure de développement. Nous avons créé :

- Le **Dockerfile** définissant l'image du serveur web : PHP 8.2 avec Apache, installation des extensions PDO, pdo_mysql, mysqli et Xdebug, activation du module `mod_rewrite` d'Apache.
- Le **docker-compose.yml** orchestrant trois services : le serveur web (`www`) exposé sur le port 8080, le serveur MySQL (`db`) sur le port 3306, et phpMyAdmin sur le port 8001.

Les volumes Docker permettent la synchronisation en temps réel du code source : toute modification dans le dossier `src/` est immédiatement reflétée dans le conteneur, sans nécessité de rebuild.

#### Phase 2 — Conception de la base de données

La base de données a été conçue avec deux tables principales :

- **`users`** : stockage des comptes utilisateurs (email unique, mot de passe haché, date de création).
- **`vehicles`** : stockage des fiches véhicules avec champs généraux (marque, modèle, année, prix, image, description) et champs de performance ajoutés ultérieurement (moteur, puissance, couple, vitesse max, 0-100 km/h).

Les scripts SQL (`database.sql` et `update.sql`) sont exécutés automatiquement par Docker lors de l'initialisation du conteneur MySQL. Cinq véhicules de test ont été insérés : Porsche 911 GT3, Tesla Model S Plaid, Toyota Prius, Ferrari F8 Tributo et BMW M3 Competition.

#### Phase 3 — Configuration centralisée (`config.php`)

Un fichier de configuration centralisé a été créé pour regrouper tous les éléments réutilisables :

- **`getDbConnection()`** : établit une connexion PDO sécurisée à MySQL avec gestion d'erreurs try-catch.
- **`startSession()`** : démarre une session PHP si elle n'est pas déjà active.
- **`isLoggedIn()`** : vérifie si un utilisateur est connecté via la session.
- **`requireLogin()`** : protège les pages nécessitant une authentification (redirection vers login.php si non connecté).
- **`escape()`** : échappe les chaînes avec `htmlspecialchars()` pour la protection XSS.

Ce principe de centralisation respecte le principe DRY (*Don't Repeat Yourself*) et facilite la maintenance du code.

#### Phase 4 — Système d'authentification

Le système d'authentification comprend trois composants :

- **Login (`login.php`)** : formulaire de connexion avec vérification des credentials via requête préparée PDO. Le mot de passe est vérifié avec `password_verify()` contre le hash bcrypt stocké en base. En cas de succès, les informations utilisateur sont stockées en session.
- **Logout (`logout.php`)** : destruction complète de la session et redirection vers la page d'accueil.
- **Création de compte (`Create_account/`)** : module complet avec formulaire HTML, validation JavaScript en temps réel (format email par regex, longueur minimale du mot de passe, confirmation du mot de passe), et soumission AJAX via l'API Fetch. Le backend vérifie l'unicité de l'email et hache le mot de passe avec bcrypt avant insertion.

#### Phase 5 — Catalogue et CRUD véhicules

- **Index / Catalogue (`index.php`)** : affichage de tous les véhicules sous forme de cartes Bootstrap dans une grille responsive (3 colonnes sur desktop, 2 sur tablette, 1 sur mobile). Chaque carte affiche l'image, la marque, le modèle, l'année, le prix et un extrait de la description.
- **Page de détail (`detail.php`)** : fiche complète d'un véhicule avec image en grand format, toutes les caractéristiques techniques dans un tableau, et boutons d'action (retour au catalogue, suppression pour les connectés).
- **Ajout (`add.php`)** : formulaire protégé par `requireLogin()`, avec champs obligatoires (marque, modèle, année, prix) et optionnels (image URL, description, caractéristiques de performance). Validation côté serveur (année entre 1900 et 2100, prix positif).
- **Suppression (`delete.php`)** : route POST-only protégée par `requireLogin()`, avec vérification de l'existence du véhicule avant suppression et message de confirmation via session flash.

#### Phase 6 — Comparateur de véhicules

Le comparateur est une fonctionnalité qui s'est ajoutée au projet comme une valeur ajoutée significative. Son fonctionnement :

1. Sur la page du catalogue, chaque carte dispose d'une checkbox en haut à droite.
2. Lorsque des véhicules sont cochés, un bouton flottant vert apparaît en bas à droite avec le compteur de véhicules sélectionnés.
3. Un clic sur ce bouton redirige vers `compare.php` avec les IDs des véhicules en paramètre GET.
4. La page de comparaison affiche un tableau horizontal avec une ligne par caractéristique (image, marque, modèle, année, prix, description, moteur, puissance, couple, vitesse max, 0-100 km/h) et une colonne par véhicule.

La requête SQL utilise un `IN` avec des placeholders dynamiques pour récupérer les véhicules sélectionnés.

#### Phase 7 — Documentation

Quatre guides Markdown ont été rédigés pour un total de plus de 1 830 lignes :

- **README.md** (271 lignes) : présentation du projet, fonctionnalités, installation, URLs, structure.
- **GUIDE_DEBUTANT.md** (718 lignes) : tutoriel complet expliquant chaque concept et chaque fichier.
- **RESUME_PROJET.md** (337 lignes) : référence rapide fichier par fichier.
- **EXPLICATION_DOCKER.md** (504 lignes) : explication détaillée de l'architecture Docker.

De plus, **chaque fichier PHP est commenté de manière exhaustive** en français, avec des blocs structurés expliquant non seulement *ce que* fait le code, mais aussi *pourquoi*.

### 3.3 Images du site

> **Figure 1** : Page d'accueil — Catalogue de véhicules avec grille responsive et checkboxes de comparaison.
>
> *(Insérer capture d'écran de la page index.php — vue catalogue avec les 5 véhicules)*

> **Figure 2** : Page de détail — Fiche complète d'un véhicule (Porsche 911 GT3) avec caractéristiques techniques.
>
> *(Insérer capture d'écran de la page detail.php)*

> **Figure 3** : Comparateur — Tableau comparatif côte-à-côte de 3 véhicules sélectionnés.
>
> *(Insérer capture d'écran de la page compare.php)*

> **Figure 4** : Page de connexion — Formulaire de login avec compte de test.
>
> *(Insérer capture d'écran de la page login.php)*

> **Figure 5** : Page de création de compte — Formulaire avec validation en temps réel.
>
> *(Insérer capture d'écran de la page Create_account/index.php)*

> **Figure 6** : Formulaire d'ajout de véhicule — Champs obligatoires et caractéristiques de performance.
>
> *(Insérer capture d'écran de la page add.php)*

> **Figure 7** : Modal de confirmation de suppression — Demande de confirmation avant suppression définitive.
>
> *(Insérer capture d'écran de la modale Bootstrap sur detail.php)*

### 3.4 Schéma relationnel de la base de données

Le schéma de la base de données `appinfo` est composé de deux tables indépendantes :

```
┌──────────────────────────────┐        ┌──────────────────────────────┐
│           USERS              │        │          VEHICLES            │
├──────────────────────────────┤        ├──────────────────────────────┤
│ id         INT (PK, AI)      │        │ id            INT (PK, AI)  │
│ email      VARCHAR(255) UNI  │        │ brand         VARCHAR(100)  │
│ password_hash VARCHAR(255)   │        │ model         VARCHAR(100)  │
│ created_at TIMESTAMP         │        │ year          INT           │
└──────────────────────────────┘        │ price         DECIMAL(10,2) │
                                        │ image_path    VARCHAR(255)  │
                                        │ description   TEXT          │
                                        │ engine        VARCHAR(50)   │
                                        │ power         INT           │
                                        │ torque        INT           │
                                        │ maxSpeed      INT           │
                                        │ zeroTOhundred DECIMAL(5,2)  │
                                        │ created_at    TIMESTAMP     │
                                        └──────────────────────────────┘
```

> **Figure 8** : Schéma relationnel de la base de données `appinfo`.

**Choix de conception :**

- Les deux tables sont **indépendantes** dans cette version : il n'y a pas de clé étrangère reliant un véhicule à l'utilisateur qui l'a ajouté. Ce choix a été fait pour simplifier le développement initial.
- Le type **DECIMAL(10,2)** est utilisé pour le prix afin de garantir la précision monétaire (éviter les erreurs d'arrondi des nombres flottants).
- Le type **DECIMAL(5,2)** est utilisé pour le 0-100 km/h afin de stocker des valeurs comme 3.4 secondes avec précision.
- Le mot de passe est stocké sous forme de **hash bcrypt** (colonne `password_hash`), jamais en clair.

**Extension future possible :** ajout d'une clé étrangère `user_id` dans la table `vehicles` pour tracer quel utilisateur a ajouté quel véhicule, et d'une table `comments` pour permettre les avis utilisateurs.

### 3.5 Difficultés rencontrées, erreurs et solutions

#### Difficulté 1 : Docker et conteneurisation (~8 heures)

**Problème :** La compréhension initiale de Docker a été notre plus gros défi technique. La syntaxe du fichier `docker-compose.yml` (langage YAML), le concept de networking entre conteneurs, et le cycle de vie des données dans les conteneurs étaient totalement nouveaux pour nous.

**Tentatives infructueuses :**
- Plusieurs configurations de ports testées avant de trouver le bon mapping (8080:80 pour le web, 3306:3306 pour MySQL).
- Problèmes de connexion entre le conteneur PHP et le conteneur MySQL : il fallait utiliser le nom du service Docker (`db`) comme hostname, et non `localhost`.
- Perte de données lors de la recréation des conteneurs : nous avons compris l'importance des volumes Docker pour la persistance.

**Solution :** Étude approfondie de la documentation officielle Docker, visionnage de tutoriels YouTube, et expérimentation progressive. Nous avons documenté notre compréhension dans le fichier `EXPLICATION_DOCKER.md` (504 lignes) pour ne pas oublier les concepts et aider d'éventuels futurs utilisateurs du projet.

#### Difficulté 2 : Conflits Git (récurrents en début de projet)

**Problème :** Git était relativement nouveau pour les deux membres de l'équipe. Les *merge conflicts* sur des fichiers partagés (notamment `config.php` et `index.php`) étaient fréquents et déroutants au début.

**Solutions apportées :**
- Apprentissage progressif des commandes `git pull`, `git merge`, et de la résolution manuelle des conflits.
- Mise en place d'une meilleure communication : définir à l'avance qui modifie quel fichier pour éviter les modifications concurrentes.
- Utilisation de branches feature pour isoler les développements.

#### Difficulté 3 : Connexion sécurisée à la base de données (~4 heures)

**Problème :** Comprendre la différence entre PDO et mysqli, le concept d'injection SQL, et l'utilisation des requêtes préparées a nécessité un travail de recherche et d'expérimentation conséquent.

**Démarche :**
- Tests initiaux avec des requêtes SQL simples (concaténation de chaînes — dangereux).
- Recherches sur les injections SQL et compréhension du risque.
- Migration vers les requêtes préparées PDO avec des placeholders `?`.

**Solution finale :** Utilisation systématique de PDO avec des requêtes préparées dans tout le projet. Exemple concret dans `login.php` :
```php
$stmt = $pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = ?');
$stmt->execute([$email]);
```

#### Difficulté 4 : Organisation des fichiers PHP (~3 heures)

**Problème :** Comment relier les pages entre elles ? Comment organiser l'arborescence ? Comment éviter la duplication de code (connexion BDD répétée dans chaque fichier) ?

**Solution :** Centralisation dans `config.php` avec des fonctions réutilisables. Toutes les pages commencent par `require_once 'config.php';`, ce qui donne accès aux fonctions de connexion, de session et de sécurité. Ce pattern simplifié s'apparente au concept MVC (Modèle-Vue-Contrôleur).

#### Difficulté 5 : Gestion des images

**Problème :** La mise en place d'un système d'upload de fichiers est complexe (validation du type de fichier, gestion du stockage, sécurité contre l'upload de scripts malveillants).

**Piste tentée :** Nous avons exploré la possibilité d'un upload natif via `$_FILES` en PHP, mais la mise en place sécurisée (vérification MIME type, limitation de taille, renommage, stockage hors du répertoire web) s'est avérée trop chronophage par rapport au temps disponible.

**Solution palliative :** Utilisation d'URLs d'images externes (depuis Unsplash notamment). Le champ `image_path` stocke une URL plutôt qu'un chemin de fichier local. Cette approche est fonctionnelle pour un prototype, mais devrait être remplacée par un vrai système d'upload en production.

### 3.6 État des lieux de la réalisation — retour au cahier des charges

#### Fonctionnalités validées

| Fonctionnalité | Statut | Détails de validation |
|---------------|--------|------------------------|
| Catalogue de véhicules | ✅ Validé | Affichage responsive de 5 véhicules de test en grille Bootstrap |
| Page de détail | ✅ Validé | Toutes les caractéristiques affichées correctement (générales + performances) |
| Comparateur | ✅ Validé | Sélection multiple, bouton flottant, tableau comparatif côte-à-côte |
| Login / Logout | ✅ Validé | Testé avec le compte admin@test.com / admin123 |
| Création de compte | ✅ Validé | Validation email (regex), mot de passe (8 car. min), confirmation, détection doublon |
| Ajout de véhicule | ✅ Validé | Formulaire fonctionnel, insertion en BDD, redirection vers la page de détail |
| Suppression de véhicule | ✅ Validé | Modal de confirmation Bootstrap, suppression effective, message flash |
| Design responsive | ✅ Validé | Testé sur mobile, tablette et desktop via les DevTools du navigateur |
| Docker | ✅ Validé | Déploiement en une commande (`docker-compose up -d`), 3 conteneurs fonctionnels |

#### Fonctionnalités non implémentées

| Fonctionnalité | Raison |
|---------------|--------|
| Pages dynamiques (contenu interactif avancé) | Manque de temps et priorisation du backend |
| Commentaires / avis utilisateurs | Fonctionnalité envisagée initialement mais hors scope final |
| Upload d'images natif | Complexité de la sécurisation (voir difficulté 5) |
| Édition / modification de véhicules | Non prioritaire par rapport aux autres fonctionnalités |
| Recherche / filtres dans le catalogue | Non implémenté, tous les véhicules sont affichés d'un coup |
| Pagination | Non nécessaire avec 5 véhicules de test, mais indispensable à l'échelle |

#### Tests effectués

- **Tests fonctionnels manuels** : chaque parcours utilisateur a été testé (inscription, connexion, ajout, consultation, comparaison, suppression, déconnexion).
- **Vérification des requêtes SQL** dans phpMyAdmin : contrôle de l'intégrité des données après insertion et suppression.
- **Tests responsive** avec les DevTools du navigateur (Chrome, Firefox) pour vérifier l'affichage sur différentes résolutions.
- **Tests de sécurité** : tentatives d'injection SQL (bloquées par les requêtes préparées), tests XSS (chaînes échappées correctement par `htmlspecialchars()`).

### 3.7 Défauts et améliorations à proposer

#### Défauts connus

- Les messages d'erreur en développement sont trop verbeux et pourraient révéler la structure de la base de données en cas d'erreur.
- Les formulaires ne disposent pas de protection CSRF (Cross-Site Request Forgery) via des tokens.
- Il n'y a pas de limitation du nombre de tentatives de connexion (vulnérabilité aux attaques par force brute).
- La politique de mot de passe est minimale (8 caractères seulement, pas d'exigence de complexité).

#### Améliorations proposées

1. **Système de rôles** : différencier administrateurs et utilisateurs standards (seuls les admins peuvent supprimer).
2. **Pagination du catalogue** : afficher 20 véhicules par page pour améliorer les performances.
3. **Filtres avancés** : recherche par marque, fourchette de prix, année, puissance.
4. **Édition de véhicules** : permettre la modification des fiches existantes.
5. **Upload d'images sécurisé** : validation du type MIME, limitation de taille, stockage sécurisé.
6. **Système de favoris** : permettre aux utilisateurs de sauvegarder des véhicules.
7. **Graphiques de comparaison** : radar chart pour visualiser les performances (Chart.js).
8. **API REST** : exposer les données pour un futur développement mobile natif.
9. **Tokens CSRF** : protéger tous les formulaires contre les attaques CSRF.
10. **Système de notation / commentaires** : enrichir l'expérience communautaire.

### 3.8 Licence du code

Le projet est distribué sous **licence MIT** (Massachusetts Institute of Technology), une licence open source permissive.

**Raisons de ce choix :**
- **Simplicité** : la licence MIT est l'une des plus courtes et les plus compréhensibles.
- **Permissivité** : elle autorise l'utilisation, la modification et la redistribution du code, y compris à des fins commerciales.
- **Attribution** : elle exige uniquement la mention des auteurs originaux.
- **Adaptée au contexte éducatif** : le projet peut servir de base à d'autres étudiants qui souhaitent apprendre le développement web avec PHP, MySQL et Docker.

```
MIT License

Copyright (c) 2026 HB & MR

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED.
```

### 3.9 Accessibilité et bonnes pratiques

#### Sécurité

Notre site implémente plusieurs mesures de sécurité essentielles :

- **Mots de passe hachés** avec l'algorithme bcrypt via `password_hash()` : les mots de passe ne sont jamais stockés en clair dans la base de données.
- **Protection contre les injections SQL** : toutes les requêtes utilisent PDO avec des requêtes préparées et des placeholders (`?`).
- **Protection contre les attaques XSS** : toutes les données affichées dans le HTML sont échappées via la fonction `escape()` (`htmlspecialchars()` avec `ENT_QUOTES` et encodage UTF-8).
- **Gestion sécurisée des sessions** : démarrage contrôlé via `startSession()`, destruction complète lors de la déconnexion.
- **Contrôle d'accès** : les pages sensibles (ajout, suppression) sont protégées par `requireLogin()`.
- **Routes POST-only** : les actions destructives (suppression) n'acceptent que les requêtes POST.

#### Organisation du code

- **Séparation des responsabilités** : la configuration est dans `config.php`, chaque page gère sa propre logique métier, et la présentation HTML est dans le même fichier (pattern simplifié).
- **Code commenté de manière exhaustive** : chaque fichier PHP contient des commentaires détaillés en français, expliquant non seulement le *quoi* mais aussi le *pourquoi*.
- **Conventions de nommage cohérentes** : noms de variables en anglais (camelCase), commentaires en français.
- **Principe DRY** : fonctions réutilisables centralisées dans `config.php`.

#### Gestion de version

- Utilisation de **Git** pour le versioning du code avec un dépôt partagé sur **GitLab**.
- Commits réguliers documentés.
- Collaboration structurée entre les deux membres de l'équipe.

#### Facilité de prise en main

- **Documentation abondante** : 4 guides Markdown totalisant plus de 1 830 lignes.
- **Setup en une commande** : `docker-compose up -d` suffit pour lancer tout l'environnement.
- **Données de test intégrées** : 5 véhicules et un compte utilisateur pré-insérés.
- **Guide d'installation pas-à-pas** dans le README.

#### Design responsive et accessible

- Utilisation du **système de grilles Bootstrap** pour l'adaptation à tous les supports.
- **Viewport meta tag** configuré pour le rendu mobile (`<meta name="viewport" content="width=device-width, initial-scale=1.0">`).
- **Sémantique HTML** respectée : utilisation des balises `<nav>`, `<header>`, `<main>`, `<footer>`.
- **Labels sur les formulaires** : chaque champ de saisie est associé à un `<label>`.
- **Textes alternatifs** sur les images (`alt` sur les balises `<img>`).
- **Navigation au clavier** fonctionnelle grâce aux composants Bootstrap natifs.

#### Numérique responsable

- **Chargement via CDN** pour Bootstrap et JavaScript (économie de bande passante serveur, mise en cache navigateur).
- **Code structuré et maintenable** : facilite les futures évolutions sans réécriture complète.
- **Pas de librairies superflues** : seuls Bootstrap et JavaScript vanilla sont utilisés, sans surcharge de dépendances.

**Axes d'amélioration en accessibilité :**
- Ajout d'attributs ARIA pour améliorer la compatibilité avec les lecteurs d'écran.
- Vérification du contraste des couleurs selon les critères WCAG.
- Mise en place d'un *lazy loading* des images pour réduire la consommation de bande passante.

---

## 4. Réalisation — Hébergement en ligne

Le projet n'a pas été déployé en ligne dans le cadre de ce module, mais reste fonctionnel en environnement local Docker. Voici les étapes nécessaires pour un hébergement en production :

**1. Choix de l'hébergeur :**
Plusieurs options s'offrent à nous selon le budget et les besoins techniques. Un VPS (Virtual Private Server) chez DigitalOcean ou OVH (~5 €/mois) permettrait d'installer Docker et de déployer l'application telle quelle. Un hébergement mutualisé classique (~3 €/mois) nécessiterait d'adapter la configuration pour utiliser Apache/PHP/MySQL installés nativement.

**2. Configuration serveur :**
Sur un VPS avec Docker, il suffirait d'installer Docker et Docker Compose, de cloner le dépôt Git, et d'exécuter `docker-compose up -d`. Sans Docker, il faudrait installer manuellement Apache, PHP 8.2 et MySQL, puis configurer les virtual hosts et les permissions.

**3. Adaptation du code :**
Le fichier `config.php` devrait être modifié pour utiliser des credentials de base de données sécurisés (et non plus `password`). L'affichage des erreurs PHP devrait être désactivé (`display_errors = Off`). Le mode debug de Xdebug devrait être retiré.

**4. Sécurité :**
La mise en place d'un certificat SSL/TLS (Let's Encrypt, gratuit) serait indispensable pour activer HTTPS. Un pare-feu devrait être configuré pour n'autoriser que les ports 22 (SSH), 80 (HTTP) et 443 (HTTPS). Des sauvegardes automatiques de la base de données devraient être mises en place.

**5. Nom de domaine :**
L'achat d'un nom de domaine (ex : `catalogue-auto.fr`, ~10 €/an) et la configuration DNS vers l'IP du serveur finaliseraient le déploiement.

**Questions à considérer :**
- Où stocker les images uploadées ? (serveur local vs stockage cloud type S3)
- Comment gérer la scalabilité si l'audience grandit ? (load balancer, réplication BDD)
- Quelles obligations légales ? (RGPD : politique de confidentialité, consentement cookies, droit à l'effacement des données personnelles)
- Qui assure la maintenance et les mises à jour de sécurité ?

---

## 5. Conclusion et réflexion

### 5.1 État global d'avancement

Le projet a atteint environ **85 % des objectifs** fixés dans le cahier des charges initial. Toutes les fonctionnalités cœur ont été implémentées et validées :

- Un **catalogue automobile dynamique** entièrement fonctionnel avec affichage responsive.
- Un **comparateur de véhicules** offrant une vue côte-à-côte détaillée.
- Un **système d'authentification complet** (inscription, connexion, déconnexion) avec une sécurité solide.
- Une **architecture Docker** permettant un déploiement en une seule commande.
- Une **documentation exceptionnelle** (plus de 1 830 lignes de guides + commentaires inline exhaustifs).

Les 15 % restants correspondent aux fonctionnalités secondaires non abouties : upload d'images natif (remplacé par des URLs), système de commentaires (initialement envisagé mais sorti du scope), et fonctionnalités avancées (recherche, pagination, édition de véhicules). Ces éléments ne compromettent pas le fonctionnement du projet mais constitueraient des améliorations pertinentes pour une version future.

Le résultat final est un **produit fonctionnel, sécurisé, déployable et évolutif**, accompagné d'une documentation permettant à n'importe quel débutant de comprendre et de reprendre le projet.

### 5.2 Auto-réflexion individuelle — HB

**Connaissances acquises :**

Ce projet m'a permis d'acquérir des compétences techniques significatives. La maîtrise de **Docker et de la conteneurisation** est sans doute l'acquis le plus important : c'est une compétence clé en DevOps, directement applicable dans mon poste en entreprise. La découverte de **PHP moderne** (paradigmes de sécurité, gestion de sessions, architecture MVC simplifiée) m'a donné une vision complète du développement backend. L'apprentissage de **Bootstrap** m'a montré comment accélérer le développement frontend avec un framework CSS. Enfin, les notions de **sécurité web** (injections SQL, XSS, hachage de mots de passe) sont des fondamentaux essentiels pour tout développeur.

**Motivation et satisfaction :**

J'ai ressenti une réelle fierté d'avoir créé un projet complet de A à Z avec une stack professionnelle. La satisfaction de voir Docker fonctionner après les difficultés initiales a été un moment marquant. Le comparateur de véhicules, qui n'était pas prévu au départ, a été particulièrement gratifiant à implémenter car il apporte une vraie valeur ajoutée au projet.

**Découvertes :**

J'ai découvert l'importance de la documentation : les 1 830 lignes que nous avons écrites ont été bénéfiques non seulement pour la compréhension du projet, mais aussi pour la collaboration. Gérer Git en équipe m'a appris que la résolution de conflits est une compétence à part entière. J'ai aussi pris conscience que le développement web est plus complexe que ce que j'imaginais, entre la sécurité, l'architecture et le responsive design.

**Défis relevés :**

Le plus grand défi a été l'apprentissage simultané de 3 à 4 technologies jamais utilisées auparavant. La frustration sur l'upload d'images (5 heures sans résultat satisfaisant) m'a appris l'importance de savoir quand pivoter vers une solution alternative.

**Apport pour ma carrière :**

Docker est maintenant un atout concret pour mon poste DevOps en entreprise. J'ai acquis une vision complète d'une application web (frontend, backend, BDD, infrastructure), ce qui me donne confiance pour aborder des frameworks plus avancés (React, Laravel, Symfony) dans le futur, car les fondamentaux sont désormais maîtrisés.

### 5.3 Auto-réflexion individuelle — MR

**Connaissances acquises :**

Ce projet a constitué ma première expérience significative en développement backend. PHP et la programmation côté serveur étaient totalement nouveaux pour moi, et j'ai pu acquérir une compréhension solide du langage et de ses paradigmes. L'apprentissage des **bases de données relationnelles** (conception de schéma, requêtes SQL, utilisation de PDO) a été un apport majeur. La **sécurité web** — comprendre *pourquoi* et *comment* protéger les données contre les injections et le vol de mots de passe — est une connaissance que je considère fondamentale. Enfin, **Git** m'a appris les bases du versioning et de la collaboration sur un même codebase.

**Motivation et satisfaction :**

Le passage de débutant complet à une personne capable de créer un système d'authentification fonctionnel est une source de grande satisfaction. Voir les données s'afficher dynamiquement depuis la base de données après des heures de travail a été un moment fort du projet. Le module de création de compte avec validation AJAX est la réalisation dont je suis le plus fier.

**Découvertes :**

J'ai découvert que la séparation frontend/backend n'est pas aussi évidente qu'il y paraît quand on débute. L'importance de tester régulièrement (pour éviter de gros bugs tard dans le développement) et de produire un code propre et commenté (qui fait gagner du temps sur le long terme) sont des leçons que je garderai.

**Défis relevés :**

La courbe d'apprentissage de PHP a été raide au début, notamment pour comprendre le flux complet : requête HTTP → traitement PHP → requête BDD → réponse HTML.

**Apport pour ma formation :**

Ce projet m'a donné des bases solides pour aborder des frameworks modernes comme Symfony ou Laravel. Les concepts universels (MVC, CRUD, sessions, authentification) que j'ai acquis sont transférables à n'importe quel langage ou framework.

### 5.4 Utilisation de l'intelligence artificielle

L'utilisation d'outils d'IA a été autorisée dans le cadre de ce projet. Nous avons utilisé **GitHub Copilot** comme assistant de développement, ainsi que **Google Gemini** pour des recherches conceptuelles.

#### Utilisation de GitHub Copilot

**Ce qui a bien fonctionné :**
- **Autocomplétion des fonctions PHP** : Copilot proposait correctement des patterns courants (connexion BDD, requêtes SQL, boucles d'affichage), ce qui nous faisait gagner du temps sur le code « boilerplate ».
- **Génération de commentaires** en français : les suggestions de commentaires descriptifs étaient souvent pertinentes et cohérentes avec notre style.
- **Suggestions de structure HTML/Bootstrap** : Copilot connaissait bien les classes Bootstrap et proposait des structures de composants cohérentes.

**Limites rencontrées :**
- Les suggestions étaient parfois **hors contexte** par rapport aux spécificités de notre projet.
- Le code généré **nécessitait toujours une relecture et une validation** : il ne suffisait pas de l'accepter aveuglément.
- Il était **impossible de déléguer l'architecture globale** du projet à l'IA : les choix structurels (organisation des fichiers, flux d'authentification, schéma BDD) devaient venir de nous.
- Les suggestions de sécurité n'étaient pas toujours optimales et devaient être vérifiées contre les bonnes pratiques.

#### Utilisation de Google Gemini

Gemini a été utilisé comme outil de recherche et de compréhension :
- **Explication de concepts** : par exemple, « quelle est la différence entre PDO et mysqli ? », « comment fonctionne une session PHP ? ».
- **Debugging** : copier un message d'erreur pour obtenir des pistes de résolution.
- **Syntaxe Docker** : aide sur la rédaction du fichier `docker-compose.yml`.
- **Brainstorming** : réflexion sur l'organisation du code et les fonctionnalités à implémenter.

#### Réflexion sur l'utilisation de l'IA

**Constat positif :** L'IA est un **accélérateur d'apprentissage**, pas un remplaçant du développeur. Elle permet de débloquer rapidement des situations et de découvrir des solutions, mais elle ne dispense pas de comprendre les fondamentaux.

**Principe que nous avons appliqué :** Ne jamais copier-coller une suggestion sans la comprendre. Chaque proposition de Copilot était lue, analysée, et soit adoptée (après compréhension), soit adaptée, soit rejetée.

**Piège évité :** La dépendance excessive à l'IA. Notre objectif principal restait l'apprentissage des fondamentaux du développement web. L'IA a été traitée comme un **tuteur interactif**, pas comme un « faiseur » qui produit le code à notre place.

**Comparaison :** L'utilisation de l'IA est comparable à celle de StackOverflow ou de la documentation officielle, mais avec une dimension plus interactive et contextuelle.

**Apprentissage méta :** Nous avons appris qu'il est important de « bien poser des questions » à l'IA (rédiger des prompts précis et contextualisés). C'est une compétence en soi qui sera de plus en plus valorisée.

**Bilan quantitatif :** Nous estimons que l'IA nous a fait gagner environ **30 à 40 % du temps** sur les tâches répétitives (code boilerplate, syntaxe, commentaires). En revanche, le temps consacré à l'apprentissage des concepts fondamentaux n'a pas été réduit : les technologies devaient être maîtrisées pour pouvoir valider ou rejeter les suggestions de l'IA.

---

## 6. Annexes

### Annexe A : Bibliographie et ressources

- **Documentation officielle PHP** : https://www.php.net/docs.php
- **Documentation Bootstrap 5** : https://getbootstrap.com/docs/5.3/
- **Documentation Docker** : https://docs.docker.com/
- **Documentation MySQL** : https://dev.mysql.com/doc/
- **Tutoriels PDO** : https://www.php.net/manual/fr/book.pdo.php
- **Unsplash** (images libres de droit) : https://unsplash.com/
- **Conversation Gemini** (cahier des charges initial) : https://gemini.google.com/share/ff65929358ae
- **GitLab du projet** : https://gitlab.com/app3-iim/dev-web-mobile

### Annexe B : Extraits de code intéressants

#### B.1 — Connexion sécurisée à la base de données (`config.php`)

```php
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
```

**Explication :** La fonction crée une connexion PDO avec des options de sécurité : le mode d'erreur est réglé sur `EXCEPTION` pour une gestion propre via try-catch, le mode de récupération par défaut est `FETCH_ASSOC` (tableau associatif), et l'émulation des requêtes préparées est désactivée pour forcer l'utilisation des vraies requêtes préparées côté MySQL.

#### B.2 — Requête préparée anti-injection SQL (`login.php`)

```php
$stmt = $pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    startSession();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    header('Location: /index.php');
    exit;
}
```

**Explication :** Le placeholder `?` empêche toute injection SQL. La fonction `password_verify()` compare le mot de passe saisi avec le hash bcrypt stocké en base, sans jamais manipuler le mot de passe en clair côté base de données.

#### B.3 — Protection XSS (`config.php`)

```php
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
```

**Explication :** Cette fonction convertit les caractères spéciaux HTML (`<`, `>`, `"`, `'`, `&`) en entités HTML, empêchant l'exécution de scripts malveillants injectés par un utilisateur.

#### B.4 — Validation AJAX avec Fetch API (`Create_account/script.js`)

```javascript
function query_data_from_base(email, password, confirme_password) {
    fetch('/Create_account/create_account.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password, confirme_password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Création réussie ! Bienvenue ' + email + ' !');
            setTimeout(() => { window.location.href = '../login.php'; }, 1000);
        } else {
            // Gestion des erreurs spécifiques (email existant, etc.)
        }
    })
    .catch(error => {
        console.error('Erreur :', error);
        alert('Erreur serveur. Veuillez réessayer.');
    });
}
```

**Explication :** Cet extrait montre l'utilisation de l'API Fetch pour envoyer les données du formulaire en JSON au backend PHP, sans rechargement de page. La réponse JSON est analysée pour afficher un message de succès ou d'erreur.

### Annexe C : Configuration Docker complète

#### C.1 — Dockerfile

```dockerfile
FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && docker-php-ext-install pdo pdo_mysql mysqli \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug

# Activation du module rewrite d'Apache pour les URLs propres
RUN a2enmod rewrite
```

#### C.2 — docker-compose.yml

```yaml
services:
  www:
    build: .
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - "./db:/docker-entrypoint-initdb.d"
    environment:
      XDEBUG_MODE: debug
      XDEBUG_CONFIG: client_host=host.docker.internal client_port=9003 start_with_request=yes

  db:
    image: mysql:latest
    ports:
      - "3306:3306"
    environment:
      - MYSQL_DATABASE=appinfo
      - MYSQL_USER=php_docker
      - MYSQL_PASSWORD=password
      - MYSQL_ALLOW_EMPTY_PASSWORD=1
    volumes:
      - "./db:/docker-entrypoint-initdb.d"

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - 8001:80
    environment:
      - PMA_POST=db
      - PMA_PORT=3306
```

### Annexe D : Scripts SQL de la base de données

#### D.1 — Création initiale (`database.sql`)

```sql
CREATE DATABASE IF NOT EXISTS appinfo;
GRANT ALL PRIVILEGES ON appinfo.* TO 'php_docker'@'%';
FLUSH PRIVILEGES;
USE appinfo;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### D.2 — Données de test et ajout de colonnes (`update.sql`)

```sql
-- Utilisateur de test (admin@test.com / admin123)
INSERT INTO users (email, password_hash) VALUES 
('admin@test.com', '$2y$10$mDLuz/7.cZMh92jAE//hbe5X1263yCqtosjr6xGus2KcrWATgHvae');

-- Véhicules de test
INSERT INTO vehicles (brand, model, year, price, image_path, description) VALUES 
('Porsche', '911 GT3', 2024, 196500.00, '...', 'Voiture sportive avec moteur flat-6 de 510 ch'),
('Tesla', 'Model S Plaid', 2024, 109990.00, '...', 'Berline électrique 1020 ch'),
('Toyota', 'Prius', 2024, 43900.00, '...', 'Hybride rechargeable économique'),
('Ferrari', 'F8 Tributo', 2024, 253000.00, '...', 'Supercar V8 biturbo 720 ch'),
('BMW', 'M3 Competition', 2024, 89900.00, '...', 'Berline sportive 6 cylindres 510 ch');

-- Ajout des colonnes de performance
ALTER TABLE vehicles ADD COLUMN maxSpeed INT;
ALTER TABLE vehicles ADD COLUMN zeroTOhundred DECIMAL(5,2);
ALTER TABLE vehicles ADD COLUMN engine VARCHAR(50);
ALTER TABLE vehicles ADD COLUMN power INT;
ALTER TABLE vehicles ADD COLUMN torque INT;

-- Mise à jour des données de performance
UPDATE vehicles SET maxSpeed=320, zeroTOhundred=3.4, engine='Flat-6', 
       power=510, torque=470 WHERE brand='Porsche' AND model='911 GT3';
-- (idem pour les 4 autres véhicules)
```

---

*Rapport rédigé par HB et MR — Février 2026*
*Projet Universitaire — Programmation Web & Application Mobile (App3)*
*Licence MIT — Copyright (c) 2026 HB & MR*
