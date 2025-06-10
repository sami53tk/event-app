# 🎉 Event Management Application

Une application Laravel moderne et complète pour la gestion d'événements avec système de paiement Stripe intégré, tests complets et CI/CD automatisé.

[![Tests](https://github.com/your-username/event-app/workflows/CI%2FCD%20Pipeline/badge.svg)](https://github.com/your-username/event-app/actions)
[![Code Quality](https://img.shields.io/badge/code%20quality-A-green.svg)](https://github.com/your-username/event-app)
[![Coverage](https://img.shields.io/badge/coverage-70%25-brightgreen.svg)](https://github.com/your-username/event-app)

## ✨ Fonctionnalités

### 🎯 Gestion des Événements
- **Création et gestion d'événements** par les organisateurs
- **Système de rôles** : Admin, Organisateur, Client
- **Inscription aux événements** avec gestion des places limitées
- **Statuts d'événements** : Actif, Annulé, Terminé
- **Bannières d'événements** avec upload d'images

### 💳 Système de Paiement
- **Intégration Stripe** pour les paiements sécurisés
- **Événements gratuits et payants**
- **Gestion des devises** (EUR par défaut)
- **Pages de succès et d'annulation** personnalisées

### 📧 Notifications Email
- **Confirmation d'inscription** automatique
- **Rappels d'événements** 24h avant
- **Notifications d'annulation** d'événements
- **Templates email** personnalisés et responsives

### 🔍 Recherche et Filtrage
- **Recherche d'événements** par titre et localisation
- **Filtres avancés** par statut, date, organisateur
- **Recherche d'utilisateurs** (admin uniquement)
- **Pagination** des résultats

### 👥 Gestion des Utilisateurs
- **Authentification complète** avec vérification email
- **Profils utilisateurs** modifiables
- **Gestion des rôles** par les administrateurs
- **Tableaux de bord** personnalisés par rôle

### 🚀 CI/CD et Qualité
- **168 tests automatisés** (437 assertions)
- **Pipeline CI/CD** avec GitHub Actions
- **Analyse statique** avec PHPStan
- **Style de code** avec Laravel Pint
- **Audit de sécurité** automatique
- **Déploiement automatisé**

## 🛠 Technologies Utilisées

- **Backend** : Laravel 11, PHP 8.2+
- **Frontend** : Blade Templates, Tailwind CSS, Alpine.js
- **Base de données** : MySQL/SQLite
- **Paiements** : Stripe API
- **Email** : Laravel Mail avec templates
- **Tests** : PHPUnit/Pest avec 168 tests
- **CI/CD** : GitHub Actions
- **Qualité** : PHPStan, Laravel Pint

## 📊 Statistiques du Projet

- ✅ **168 tests** passants
- ✅ **437 assertions** validées
- ✅ **70%+ couverture** de code
- ✅ **Niveau 3 PHPStan** d'analyse statique
- ✅ **Style de code** Laravel conforme
- ✅ **0 vulnérabilité** de sécurité

## 🚀 Installation Rapide

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js et NPM
- MySQL ou SQLite

### Installation en une commande
```bash
git clone <repository-url> event-app && cd event-app && composer install && npm install && cp .env.example .env && php artisan key:generate
```

## 🧪 Tests

### Exécuter tous les tests
```bash
php artisan test
```

### Tests avec couverture
```bash
php artisan test --coverage --min=70
```

### Tests par catégorie
```bash
# Tests unitaires uniquement
php artisan test --testsuite=Unit

# Tests de fonctionnalités uniquement
php artisan test --testsuite=Feature

# Tests spécifiques
php artisan test --filter=EventControllerTest
```

### Structure des tests
- **Tests unitaires** : Modèles, méthodes isolées
- **Tests de fonctionnalités** : Contrôleurs, intégrations
- **Tests d'authentification** : Login, registration, permissions
- **Tests de paiement** : Stripe, checkout, webhooks
- **Tests d'email** : Templates, envoi, contenu

## 🔍 Qualité du Code

### Analyse statique avec PHPStan
```bash
./vendor/bin/phpstan analyse --memory-limit=2G
```

### Vérification du style de code
```bash
./vendor/bin/pint --test
```

### Correction automatique du style
```bash
./vendor/bin/pint
```

### Audit de sécurité
```bash
composer audit
```

## 🚀 CI/CD Pipeline

### Workflows GitHub Actions
- **CI Pipeline** : Tests, qualité, sécurité
- **Deploy Pipeline** : Déploiement staging/production
- **Triggers** : Push, PR, déclenchement manuel

### Configuration
Voir [docs/CI-CD.md](docs/CI-CD.md) pour la configuration complète.

## 📁 Structure du Projet

```
event-app/
├── app/
│   ├── Console/Commands/     # Commandes Artisan
│   ├── Http/Controllers/     # Contrôleurs
│   ├── Jobs/                 # Jobs en arrière-plan
│   ├── Mail/                 # Classes d'email
│   ├── Models/               # Modèles Eloquent
│   └── Listeners/            # Écouteurs d'événements
├── database/
│   ├── migrations/           # Migrations de base de données
│   ├── seeders/              # Seeders de données
│   └── factories/            # Factories pour les tests
├── tests/
│   ├── Unit/                 # Tests unitaires
│   └── Feature/              # Tests de fonctionnalités
├── .github/workflows/        # Workflows GitHub Actions
├── scripts/                  # Scripts de déploiement
└── docs/                     # Documentation
```

## 🔗 API et Endpoints

### Événements Publics
- `GET /public-events` - Liste des événements publics
- `GET /events/{event}` - Détails d'un événement

### Gestion des Événements (Auth requise)
- `GET /events` - Liste des événements (admin/organisateur)
- `POST /events` - Créer un événement
- `PUT /events/{event}` - Modifier un événement
- `DELETE /events/{event}` - Supprimer un événement

### Inscriptions
- `POST /events/{event}/register` - S'inscrire à un événement
- `DELETE /events/{event}/unregister` - Se désinscrire

### Paiements
- `GET /payment/{event}/show` - Page de paiement
- `POST /payment/{event}/checkout` - Créer une session Stripe
- `GET /payment/{event}/success` - Succès du paiement
- `GET /payment/{event}/cancel` - Annulation du paiement

## 👤 Comptes de Démonstration

Après avoir exécuté les seeders :

### Administrateur
- **Email** : admin@example.com
- **Mot de passe** : password

### Organisateur
- **Email** : organizer@example.com
- **Mot de passe** : password

### Client
- **Email** : client@example.com
- **Mot de passe** : password

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

### Standards de Code
- Suivre les conventions Laravel
- Utiliser Laravel Pint pour le style de code
- Écrire des tests pour les nouvelles fonctionnalités
- Maintenir la couverture de tests > 70%

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🆘 Support

Pour toute question ou problème :
- Ouvrir une issue sur GitHub
- Consulter la documentation dans `/docs`
- Vérifier les logs dans `storage/logs`

---

**Développé avec ❤️ en utilisant Laravel 11**
