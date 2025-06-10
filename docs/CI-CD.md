# CI/CD Pipeline Documentation

## Vue d'ensemble

Ce projet utilise GitHub Actions pour l'intégration continue (CI) et le déploiement continu (CD). Le pipeline est divisé en plusieurs workflows pour une meilleure organisation et flexibilité.

## Workflows

### 1. CI Pipeline (`.github/workflows/ci.yml`)

Ce workflow s'exécute sur chaque push et pull request vers les branches `main` et `develop`.

#### Jobs

**Tests**
- Exécute les tests sur PHP 8.2 et 8.3
- Utilise SQLite en mémoire pour les tests
- Génère un rapport de couverture de code
- Upload la couverture vers Codecov

**Code Quality**
- Vérifie le style de code avec Laravel Pint
- Analyse statique avec PHPStan
- Niveau d'analyse PHPStan : 5

**Security**
- Audit de sécurité des dépendances Composer
- Vérification des vulnérabilités connues

### 2. Deploy Pipeline (`.github/workflows/deploy.yml`)

Ce workflow gère les déploiements vers les environnements staging et production.

#### Déclencheurs

- **Staging** : Push vers `develop` ou déclenchement manuel
- **Production** : Push vers `main` ou déclenchement manuel

#### Étapes de déploiement

1. **Préparation**
   - Installation des dépendances PHP et Node.js
   - Compilation des assets
   - Optimisation Composer

2. **Déploiement**
   - Synchronisation des fichiers via rsync
   - Configuration de l'environnement
   - Exécution des migrations
   - Mise en cache des configurations Laravel

3. **Vérification**
   - Test de santé de l'application
   - Notifications Slack (optionnel)

## Configuration des Secrets

### Secrets GitHub requis

#### Pour le déploiement Staging
```
SSH_PRIVATE_KEY          # Clé SSH privée pour l'accès au serveur
STAGING_HOST            # Adresse du serveur staging
STAGING_USER            # Utilisateur SSH pour staging
STAGING_PATH            # Chemin de déploiement sur staging
STAGING_URL             # URL de l'application staging
```

#### Pour le déploiement Production
```
PRODUCTION_HOST         # Adresse du serveur production
PRODUCTION_USER         # Utilisateur SSH pour production
PRODUCTION_PATH         # Chemin de déploiement sur production
PRODUCTION_URL          # URL de l'application production
```

#### Optionnel
```
SLACK_WEBHOOK           # Webhook Slack pour les notifications
CODECOV_TOKEN           # Token pour l'upload de couverture
```

## Configuration des Environnements

### Staging
- Fichier de configuration : `.env.staging`
- Base de données : `event_app_staging`
- URL : `https://staging.event-app.com`
- Stripe : Clés de test

### Production
- Fichier de configuration : `.env.production`
- Base de données : `event_app_production`
- URL : `https://event-app.com`
- Stripe : Clés de production

## Scripts de Déploiement

### Script manuel (`scripts/deploy.sh`)

Permet un déploiement manuel depuis votre machine locale :

```bash
# Déploiement vers staging
./scripts/deploy.sh staging

# Déploiement vers production
./scripts/deploy.sh production
```

#### Fonctionnalités
- Sauvegarde automatique avant déploiement
- Vérification des prérequis
- Test de connexion SSH
- Synchronisation des fichiers
- Configuration de l'environnement
- Vérification post-déploiement
- Nettoyage des anciennes sauvegardes

## Qualité du Code

### Laravel Pint
Configuration dans `pint.json` :
- Preset Laravel
- Règles personnalisées pour la cohérence
- Exclusion des dossiers système

### PHPStan
Configuration dans `phpstan.neon` :
- Niveau d'analyse : 5
- Extension Larastan pour Laravel
- Exclusions pour les faux positifs courants

### Tests
- **168 tests** avec **437 assertions**
- Couverture minimale : 70%
- Tests unitaires et fonctionnels
- Base de données SQLite en mémoire

## Monitoring et Notifications

### Slack (Optionnel)
- Notifications de succès/échec des déploiements
- Canal : `#deployments`
- Webhook configurable via secrets

### Codecov
- Rapport de couverture de code
- Intégration avec les pull requests
- Historique de la couverture

## Sécurité

### Bonnes Pratiques
- Secrets stockés dans GitHub Secrets
- Clés SSH avec permissions restreintes
- Audit automatique des dépendances
- Environnements protégés

### Permissions Serveur
```bash
# Permissions recommandées
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Dépannage

### Échec des Tests
1. Vérifier les logs GitHub Actions
2. Exécuter les tests localement : `php artisan test`
3. Vérifier la configuration de la base de données

### Échec du Déploiement
1. Vérifier la connectivité SSH
2. Contrôler les permissions sur le serveur
3. Vérifier les variables d'environnement
4. Consulter les logs du serveur web

### Problèmes de Style de Code
```bash
# Corriger automatiquement
./vendor/bin/pint

# Vérifier sans corriger
./vendor/bin/pint --test
```

### Problèmes PHPStan
```bash
# Exécuter localement
./vendor/bin/phpstan analyse

# Avec plus de mémoire
./vendor/bin/phpstan analyse --memory-limit=2G
```

## Maintenance

### Mise à jour des Dépendances
1. Mettre à jour `composer.json` et `package.json`
2. Tester localement
3. Créer une pull request
4. Les tests automatiques valideront les changements

### Rotation des Clés SSH
1. Générer une nouvelle paire de clés
2. Mettre à jour le secret `SSH_PRIVATE_KEY`
3. Déployer la clé publique sur les serveurs

### Sauvegarde
- Sauvegardes automatiques avant chaque déploiement
- Conservation des 5 dernières sauvegardes
- Sauvegarde manuelle recommandée avant les mises à jour majeures
