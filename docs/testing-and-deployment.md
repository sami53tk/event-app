# Guide de test et déploiement

Ce document explique comment exécuter les tests et déployer l'application Event App.

## Tests

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- SQLite (pour les tests)
- Xdebug (pour la couverture de code)

### Exécution des tests

Pour exécuter tous les tests :

```bash
php artisan test
```

Ou avec Pest directement :

```bash
vendor/bin/pest
```

### Couverture de code

Pour générer un rapport de couverture de code, vous devez avoir Xdebug installé et activé. Ensuite, exécutez :

```bash
vendor/bin/pest --coverage
```

Pour un rapport HTML détaillé :

```bash
XDEBUG_MODE=coverage vendor/bin/pest --coverage-html reports/
```

Cela générera un rapport HTML dans le dossier `reports/`.

## Déploiement CI/CD

### GitHub Actions

Le projet est configuré avec GitHub Actions pour l'intégration continue et le déploiement continu. Le workflow est défini dans le fichier `.github/workflows/ci.yml`.

Le pipeline CI/CD effectue les actions suivantes :

1. **Tests** : Exécute tous les tests unitaires et fonctionnels
2. **Analyse statique** : Exécute PHPStan pour l'analyse statique du code
3. **Déploiement** : Si les tests réussissent et que le push est sur la branche main, déploie l'application

### Déploiement manuel

Pour déployer manuellement l'application :

1. Clonez le dépôt sur le serveur
2. Installez les dépendances :
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```
3. Configurez le fichier `.env` avec les informations de production
4. Générez une clé d'application :
   ```bash
   php artisan key:generate
   ```
5. Exécutez les migrations :
   ```bash
   php artisan migrate --force
   ```
6. Configurez le serveur web (Apache/Nginx) pour pointer vers le dossier `public/`

### Variables d'environnement importantes

Assurez-vous de configurer correctement les variables d'environnement suivantes en production :

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://votre-domaine.com`
- `DB_CONNECTION=mysql`
- `DB_HOST=votre-hote-db`
- `DB_PORT=3306`
- `DB_DATABASE=votre-base-de-donnees`
- `DB_USERNAME=votre-utilisateur`
- `DB_PASSWORD=votre-mot-de-passe`
- `STRIPE_KEY=votre-cle-publique-stripe`
- `STRIPE_SECRET=votre-cle-secrete-stripe`

## Intégration Stripe

Pour que le système de paiement fonctionne correctement, vous devez configurer les clés API Stripe dans votre fichier `.env` :

```
STRIPE_KEY=pk_test_votre_cle_publique
STRIPE_SECRET=sk_test_votre_cle_secrete
```

En production, remplacez les clés de test par les clés de production.
