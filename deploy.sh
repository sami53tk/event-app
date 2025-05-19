#!/bin/bash

# Script de déploiement pour l'application Event App
# Usage: ./deploy.sh [staging|production]

# Vérifier si l'environnement est spécifié
if [ -z "$1" ]; then
    echo "Usage: ./deploy.sh [staging|production]"
    exit 1
fi

# Définir les variables en fonction de l'environnement
if [ "$1" = "staging" ]; then
    ENV="staging"
    HOST="staging.eventapp.example.com"
    USER="deploy"
    PATH_DEPLOY="/var/www/staging.eventapp.example.com"
    BRANCH="develop"
elif [ "$1" = "production" ]; then
    ENV="production"
    HOST="eventapp.example.com"
    USER="deploy"
    PATH_DEPLOY="/var/www/eventapp.example.com"
    BRANCH="main"
else
    echo "Environnement non valide. Utilisez 'staging' ou 'production'."
    exit 1
fi

echo "Déploiement vers l'environnement $ENV..."

# Vérifier que nous sommes sur la bonne branche
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
    echo "Vous n'êtes pas sur la branche $BRANCH. Veuillez basculer sur la branche $BRANCH avant de déployer."
    exit 1
fi

# Vérifier que le dépôt local est à jour
git fetch origin
LOCAL=$(git rev-parse @)
REMOTE=$(git rev-parse origin/$BRANCH)

if [ $LOCAL != $REMOTE ]; then
    echo "Votre branche locale n'est pas à jour avec origin/$BRANCH. Veuillez faire un pull avant de déployer."
    exit 1
fi

# Vérifier que tous les changements sont commités
if [ -n "$(git status --porcelain)" ]; then
    echo "Vous avez des changements non commités. Veuillez commiter ou stasher vos changements avant de déployer."
    exit 1
fi

# Construire les assets
echo "Construction des assets..."
npm ci
npm run build

# Installer les dépendances de production
echo "Installation des dépendances de production..."
composer install --no-dev --optimize-autoloader

# Déployer vers le serveur
echo "Déploiement vers $HOST..."
rsync -avz --exclude='.git' \
          --exclude='node_modules' \
          --exclude='tests' \
          --exclude='.github' \
          --exclude='.env.example' \
          --exclude='phpunit.xml' \
          --exclude='README.md' \
          ./ $USER@$HOST:$PATH_DEPLOY

# Configurer l'environnement sur le serveur
echo "Configuration de l'environnement sur le serveur..."
ssh $USER@$HOST "cd $PATH_DEPLOY && \
cp .env.$ENV .env && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
php artisan migrate --force && \
php artisan storage:link && \
chmod -R 775 storage bootstrap/cache"

echo "Déploiement terminé avec succès !"
