<p align="center"><a href="#" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Event App Logo"></a></p>

<p align="center">
<a href="https://github.com/yourusername/event-app/actions"><img src="https://github.com/yourusername/event-app/workflows/CI/CD%20Pipeline/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/yourusername/event-app"><img src="https://codecov.io/gh/yourusername/event-app/branch/main/graph/badge.svg" alt="Code Coverage"></a>
<a href="https://github.com/yourusername/event-app/blob/main/LICENSE"><img src="https://img.shields.io/github/license/yourusername/event-app" alt="License"></a>
</p>

# Event App - Application de gestion d'événements

## À propos de l'application

Event App est une application web complète de gestion d'événements développée avec Laravel. Elle permet aux organisateurs de créer et gérer des événements, aux clients de s'inscrire et de payer pour des événements, et aux administrateurs de superviser l'ensemble du système.

## Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- MySQL ou autre base de données compatible
- Compte Stripe pour les paiements (optionnel)

### Installation locale

1. Cloner le dépôt
```bash
git clone https://github.com/yourusername/event-app.git
cd event-app
```

2. Installer les dépendances
```bash
composer install
npm install
```

3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans le fichier `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_app
DB_USERNAME=root
DB_PASSWORD=
```

5. Configurer Stripe (optionnel)
```
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
```

6. Exécuter les migrations et les seeders
```bash
php artisan migrate --seed
```

7. Compiler les assets
```bash
npm run build
```

8. Démarrer le serveur
```bash
php artisan serve
```

## CI/CD

L'application utilise GitHub Actions pour l'intégration continue et le déploiement continu. Le pipeline CI/CD comprend les étapes suivantes :

### Intégration Continue (CI)
- Exécution des tests unitaires et fonctionnels
- Analyse statique du code avec PHPStan
- Vérification du style de code avec Laravel Pint
- Génération de rapports de couverture de code

### Déploiement Continu (CD)
- Déploiement automatique vers l'environnement de staging lors d'un push sur la branche `develop`
- Déploiement automatique vers l'environnement de production lors d'un push sur la branche `main`
- Déploiement manuel vers staging ou production via le workflow dispatch

### Configuration des secrets GitHub

Pour que le pipeline CI/CD fonctionne correctement, vous devez configurer les secrets suivants dans votre dépôt GitHub :

- `SSH_PRIVATE_KEY` : Clé SSH privée pour se connecter aux serveurs
- `STAGING_HOST` : Nom d'hôte du serveur de staging
- `STAGING_USER` : Nom d'utilisateur pour le serveur de staging
- `STAGING_PATH` : Chemin d'accès au répertoire de déploiement sur le serveur de staging
- `PRODUCTION_HOST` : Nom d'hôte du serveur de production
- `PRODUCTION_USER` : Nom d'utilisateur pour le serveur de production
- `PRODUCTION_PATH` : Chemin d'accès au répertoire de déploiement sur le serveur de production
- `SLACK_WEBHOOK` : URL du webhook Slack pour les notifications
- `STRIPE_TEST_KEY` : Clé publique Stripe pour les tests
- `STRIPE_TEST_SECRET` : Clé secrète Stripe pour les tests
- `CODECOV_TOKEN` : Token pour l'intégration avec Codecov

### Déploiement manuel

Un script de déploiement manuel est également disponible :

```bash
./deploy.sh [staging|production]
```

## Tests

L'application dispose d'une suite complète de tests unitaires et fonctionnels. Pour exécuter les tests :

```bash
php artisan test
```

Pour générer un rapport de couverture de code :

```bash
php artisan test --coverage
```

## Simulation du planificateur de tâches

Pour simuler le planificateur de tâches en développement :

```bash
while true; do php artisan schedule:run; sleep 60; done
```

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.



## Fonctionnalités

### Gestion des utilisateurs
- Authentification
  - Inscription des utilisateurs
  - Connexion/déconnexion
  - Vérification d'email
  - Réinitialisation de mot de passe
  - Confirmation de mot de passe pour les actions sensibles
- Gestion des profils
  - Mise à jour des informations personnelles
  - Modification du mot de passe
  - Suppression de compte
- Rôles et permissions
  - Rôle administrateur (admin)
  - Rôle organisateur (organisateur)
  - Rôle client (client)
  - Restrictions d'accès basées sur les rôles

### Gestion des événements
- Création et gestion d'événements
  - Création d'événements avec titre, description, date, lieu
  - Téléchargement de bannières pour les événements
  - Définition du nombre maximum de participants
  - Configuration du prix et de la devise (intégration Stripe)
  - Modification des détails de l'événement
  - Suppression d'événements
- Statuts des événements
  - Événements actifs
  - Événements annulés
  - Événements terminés
- Filtrage et recherche
  - Affichage des événements par statut
  - Affichage des événements par organisateur
  - Recherche d'événements

### Inscription aux événements
- Processus d'inscription
  - Inscription à des événements gratuits
  - Inscription à des événements payants (redirection vers paiement)
  - Vérification de la disponibilité des places
  - Désinscription des événements

### Paiement
- Intégration avec Stripe pour les paiements
- Page de checkout sécurisée
- Confirmation de paiement
- Gestion des annulations de paiement

### Notifications et rappels
- Emails
  - Email de confirmation d'inscription à un événement
  - Email de rappel avant le début d'un événement
  - Email d'annulation d'événement
  - Email de vérification de compte
- Jobs en arrière-plan
  - Envoi automatique de rappels pour les événements imminents

### Tableaux de bord
- Tableau de bord administrateur
  - Gestion de tous les utilisateurs
  - Gestion de tous les événements
  - Statistiques globales
- Tableau de bord organisateur
  - Gestion de ses propres événements
  - Statistiques sur les inscriptions
  - Liste des participants
- Tableau de bord client
  - Liste des événements auxquels le client est inscrit
  - Historique des paiements

### Fonctionnalités techniques
- Sécurité
  - Protection CSRF
  - Validation des formulaires
  - Middleware d'authentification et d'autorisation
  - Hachage des mots de passe
- Performance
  - Pagination des résultats
  - Optimisation des requêtes de base de données
- Tests
  - Tests unitaires
  - Tests fonctionnels
  - Tests d'intégration
  - Couverture de code (84%)


## User

# Administrateur
Email : admin@example.com
Mot de passe : password123

# Organisateurs
Email : organisateur1@example.com (Association Culturelle de Paris)
Email : organisateur2@example.com (Club Sportif Marseillais)
Email : organisateur3@example.com (Conférences Tech Lyon)
Mot de passe : password123 (pour tous)

# Clients
Email : client1@example.com (Sophie Martin)
Email : client2@example.com (Thomas Dubois)
Email : client3@example.com (Emma Bernard)
Mot de passe : password123 (pour tous)

clear cache:

composer dump-autoload
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan clear-compiled

composer run dev

php artisan test
php artisan test --coverage

simulation cron:
while true; do php artisan schedule:run; sleep 60; done