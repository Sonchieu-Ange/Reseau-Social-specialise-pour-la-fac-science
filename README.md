# FacSocial — Réseau Social de la Faculté des Sciences

> Plateforme sociale interne dédiée à la communauté académique de la Faculté des Sciences de l'Université de Ngaoundéré.

---

## Table des matières

- [À propos du projet](#à-propos-du-projet)
- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Architecture](#architecture)
- [Modèle de données](#modèle-de-données)
- [Rôles & Permissions](#rôles--permissions)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Sécurité](#sécurité)
- [Perspectives d'évolution](#perspectives-dévolution)
- [Auteurs](#auteurs)

---

## À propos du projet

La Faculté des Sciences de l'Université de Ngaoundéré regroupe étudiants, enseignants et personnel administratif, avec des besoins croissants en matière de collaboration en ligne. Faute de plateforme numérique interne, les échanges étaient jusqu'ici dispersés sur des réseaux généralistes sans espace dédié à la vie académique.

**FacSocial** est un réseau social interne sécurisé qui centralise :
- la communication entre membres de la faculté,
- les annonces et événements académiques,
- les groupes thématiques et la messagerie privée,
- l'administration et la modération des contenus.

---

## Fonctionnalités

| Module | Description |
|---|---|
| **Authentification** | Inscription avec choix de rôle, connexion/déconnexion, protection des routes par middleware |
| **Publications** | Création multimédia (texte, image, vidéo, document), fil d'actualité, modification et suppression par l'auteur |
| **Interactions** | Likes (toggle), commentaires, compteurs en temps réel |
| **Groupes** | Création, adhésion, départ, messagerie interne, gestion par le créateur |
| **Événements** | Création, participation, annulation, liste des participants |
| **Annonces** | CRUD réservé aux enseignants et administrateurs, consultation publique |
| **Communautés** | Regroupement de groupes thématiques, gestion par les créateurs et admins |
| **Profils** | Consultation publique, modification par le propriétaire uniquement |
| **Messagerie privée** | Conversations un-à-un, liste des échanges, affichage chronologique |
| **Administration** | Tableau de bord `/admin`, gestion des utilisateurs, modération des contenus |

---

## Stack technique

| Couche | Technologie |
|---|---|
| **Backend** | Laravel 13 (PHP 8.4) |
| **Base de données** | MongoDB (via `mongodb/laravel-mongodb`) |
| **Frontend** | Bootstrap 5 + Blade Templates |
| **Routing & Sécurité** | Middleware `auth` + `AdminMiddleware` |
| **Stockage médias** | Stockage local (images, vidéos, PDF, documents) |

---

## Architecture

Le projet suit une architecture **MVC stricte** (Laravel) :

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── PublicationController.php
│   │   ├── GroupeController.php
│   │   ├── EvenementController.php
│   │   ├── AdminController.php
│   │   └── ...
│   └── Middleware/
│       ├── Authenticate.php
│       └── AdminMiddleware.php
├── Models/
│   ├── Utilisateur.php
│   ├── Publication.php
│   ├── Commentaire.php
│   ├── Like.php
│   ├── Groupe.php
│   ├── Evenement.php
│   ├── Annonce.php
│   ├── Communaute.php
│   └── Message.php
resources/
└── views/
    ├── auth/
    ├── feed/
    ├── groupes/
    ├── evenements/
    ├── admin/
    ├── profil/
    └── messages/
routes/
└── web.php
```

---

## Modèle de données

Le projet utilise **MongoDB** avec les collections suivantes :

| Collection | Champs principaux |
|---|---|
| `utilisateurs` | `_id`, `nom`, `email`*, `mot_de_passe` (bcrypt), `role`, `bio`, `avatar` |
| `publications` | `_id`, `contenu`, `type_media`, `url_media`, `createur_id` |
| `commentaires` | `_id`, `contenu`, `auteur_id`, `publication_id` |
| `likes` | `_id`, `utilisateur_id`, `publication_id` *(index unique composé)* |
| `groupes` | `_id`, `nom`, `description`, `createur_id`, `communaute_id` |
| `groupes_membres` | `_id`, `groupe_id`, `utilisateur_id`, `role` *(index unique composé)* |
| `messages_groupes` | `_id`, `contenu`, `auteur_id`, `groupe_id` |
| `evenements` | `_id`, `titre`, `description`, `date`, `lieu`, `createur_id` |
| `evenements_participants` | `_id`, `evenement_id`, `utilisateur_id` *(index unique composé)* |
| `annonces` | `_id`, `titre`, `contenu`, `createur_id` |
| `communautes` | `_id`, `nom`, `description`, `createur_id` |
| `messages` | `_id`, `contenu`, `expediteur_id`, `destinataire_id`, `lu` (bool) |

---

## Rôles & Permissions

| Rôle | Accès |
|---|---|
| **Visiteur** | Page d'accueil publique, formulaire d'inscription uniquement |
| **Étudiant** | Publications, likes, commentaires, groupes, événements, messagerie privée |
| **Enseignant** | Droits étudiant + création de groupes, événements, annonces et communautés |
| **Administrateur** | Accès complet à `/admin` : gestion utilisateurs, modération des contenus |

---

## Installation

### Prérequis

- PHP 8.4+
- Composer
- MongoDB (local ou Atlas)
- Extension PHP MongoDB activée
- Node.js & npm (pour les assets front-end)

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/<votre-org>/facsocial.git
cd facsocial

# 2. Installer les dépendances PHP
composer install

# 3. Copier et configurer l'environnement
cp .env.example .env
# Renseigner les variables MONGODB_URI, DB_DATABASE, APP_KEY, etc.

# 4. Générer la clé d'application
php artisan key:generate

# 5. Installer les dépendances front-end
npm install && npm run build

# 6. Lancer le serveur de développement
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`.

---

## Utilisation

### Créer un compte

Accéder à `/register`, renseigner nom, email, mot de passe et sélectionner un rôle (`etudiant` ou `enseignant`).

### Publier du contenu

Depuis le fil d'actualité, cliquer sur **Nouvelle publication** et joindre optionnellement un fichier multimédia.

### Rejoindre un groupe

Naviguer vers la section **Groupes**, rechercher un groupe et cliquer sur **Rejoindre**.

### Accès administration

Connectez-vous avec un compte `admin` et accéder à `/admin` pour le tableau de bord.

---

## Sécurité

- Mots de passe hachés via **bcrypt** (`Hash::make`)
- Protection **CSRF** sur tous les formulaires POST/PUT/DELETE
- Middleware `auth` sur toutes les routes protégées
- `AdminMiddleware` vérifiant le rôle admin sur les routes `/admin`
- Validation serveur stricte (`required`, `unique`, `mimes`) sur tous les champs
- Seul l'auteur peut modifier ou supprimer sa propre ressource

---

## Perspectives d'évolution

- **Notifications temps réel** — WebSockets via Pusher / Laravel Echo
- **Chat temps réel** — messagerie privée et de groupe avec affichage instantané
- **Application mobile** — version React Native ou Flutter (iOS & Android)
- **Recherche avancée** — moteur full-text Elasticsearch sur publications et utilisateurs

---

## Auteurs

Projet réalisé dans le cadre du cours **INF336 – Ingénierie des Applications Web**

| # | Nom & Prénom | Matricule |
|---|---|---|
| 1 | BAYANG KAMDI FRECHINOS | 23A248FS |
| 2 | HAMOA PIERRE CELESTIN| 23B382FS |
| 3 | KAMWA DANIEL | 20A171FS |
| 4 | MOHAMADOU AWALOU| 21A286FS |
| 5 | OUDJAIROU ISSIAKOU | 23B295FS |
| 6 | OUSMANE IDRISS ADAM | 23A238FS |
| 7 | SONCHIEU ANGE | 25A011FS |
| 8 | WANGBARA BERNARD | 23A897FS |

**Encadrants :** Prof. Dr.-Ing. DAYANG PAUL & M. KOTVA Samuel

**Université de Ngaoundéré — Faculté des Sciences — Département de Mathématique Informatique**
Année académique : 2025-2026
