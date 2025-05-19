# Configuration du CI/CD pour Event App

Ce document explique comment configurer et utiliser le pipeline CI/CD pour l'application Event App.

## Aperçu du pipeline CI/CD

Le pipeline CI/CD est configuré dans le fichier `.github/workflows/ci.yml` et comprend les étapes suivantes :

1. **Tests et qualité du code** : Exécution des tests unitaires et fonctionnels, analyse statique du code, vérification du style de code.
2. **Déploiement vers staging** : Déploiement automatique vers l'environnement de staging lors d'un push sur la branche `develop`.
3. **Déploiement vers production** : Déploiement automatique vers l'environnement de production lors d'un push sur la branche `main`.

## Configuration des secrets GitHub

Pour que le pipeline CI/CD fonctionne correctement, vous devez configurer les secrets suivants dans votre dépôt GitHub :

### Secrets pour les tests

- `STRIPE_TEST_KEY` : Clé publique Stripe pour les tests
- `STRIPE_TEST_SECRET` : Clé secrète Stripe pour les tests
- `CODECOV_TOKEN` : Token pour l'intégration avec Codecov (optionnel)

### Secrets pour le déploiement vers staging

- `SSH_PRIVATE_KEY` : Clé SSH privée pour se connecter aux serveurs
- `STAGING_HOST` : Nom d'hôte du serveur de staging
- `STAGING_USER` : Nom d'utilisateur pour le serveur de staging
- `STAGING_PATH` : Chemin d'accès au répertoire de déploiement sur le serveur de staging

### Secrets pour le déploiement vers production

- `PRODUCTION_HOST` : Nom d'hôte du serveur de production
- `PRODUCTION_USER` : Nom d'utilisateur pour le serveur de production
- `PRODUCTION_PATH` : Chemin d'accès au répertoire de déploiement sur le serveur de production

### Secrets pour les notifications

- `SLACK_WEBHOOK` : URL du webhook Slack pour les notifications

## Comment configurer les secrets GitHub

1. Accédez à votre dépôt GitHub
2. Cliquez sur "Settings" (Paramètres)
3. Dans le menu de gauche, cliquez sur "Secrets and variables" puis "Actions"
4. Cliquez sur "New repository secret"
5. Entrez le nom du secret et sa valeur
6. Cliquez sur "Add secret"

## Configuration des environnements GitHub

Pour utiliser les environnements GitHub (staging et production), vous devez les configurer :

1. Accédez à votre dépôt GitHub
2. Cliquez sur "Settings" (Paramètres)
3. Dans le menu de gauche, cliquez sur "Environments"
4. Cliquez sur "New environment"
5. Entrez le nom de l'environnement (staging ou production)
6. Configurez les règles de protection si nécessaire (par exemple, exiger une approbation pour le déploiement en production)
7. Cliquez sur "Configure environment"

## Activer le déploiement

Par défaut, les jobs de déploiement sont commentés dans le fichier `.github/workflows/ci.yml`. Une fois que vous avez configuré tous les secrets nécessaires, vous pouvez décommenter ces sections pour activer le déploiement automatique.

## Déploiement manuel

Vous pouvez également déclencher un déploiement manuel via l'interface GitHub Actions :

1. Accédez à votre dépôt GitHub
2. Cliquez sur "Actions"
3. Sélectionnez le workflow "CI/CD Pipeline"
4. Cliquez sur "Run workflow"
5. Sélectionnez la branche et l'environnement (staging ou production)
6. Cliquez sur "Run workflow"

## Déploiement en ligne de commande

Un script de déploiement manuel est également disponible :

```bash
./deploy.sh [staging|production]
```

## Résolution des problèmes

### Les tests échouent

- Vérifiez que toutes les dépendances sont installées
- Vérifiez que les variables d'environnement sont correctement configurées
- Exécutez les tests localement pour identifier le problème

### Le déploiement échoue

- Vérifiez que tous les secrets sont correctement configurés
- Vérifiez que le serveur est accessible via SSH
- Vérifiez que l'utilisateur a les permissions nécessaires sur le serveur
- Vérifiez les logs de déploiement pour plus de détails

## Ressources supplémentaires

- [Documentation GitHub Actions](https://docs.github.com/en/actions)
- [Documentation Laravel sur le déploiement](https://laravel.com/docs/deployment)
- [Documentation Stripe](https://stripe.com/docs)
