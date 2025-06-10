#!/bin/bash

# Script de déploiement pour Event App
# Usage: ./scripts/deploy.sh [staging|production]

set -e

ENVIRONMENT=${1:-staging}
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo "🚀 Déploiement vers l'environnement: $ENVIRONMENT"
echo "📅 Timestamp: $TIMESTAMP"

# Couleurs pour les logs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour logger
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

# Vérification de l'environnement
if [[ "$ENVIRONMENT" != "staging" && "$ENVIRONMENT" != "production" ]]; then
    error "Environnement invalide. Utilisez 'staging' ou 'production'"
fi

# Configuration selon l'environnement
if [[ "$ENVIRONMENT" == "staging" ]]; then
    SERVER_HOST=${STAGING_HOST:-"staging.event-app.com"}
    SERVER_USER=${STAGING_USER:-"deploy"}
    SERVER_PATH=${STAGING_PATH:-"/var/www/staging"}
    ENV_FILE=".env.staging"
elif [[ "$ENVIRONMENT" == "production" ]]; then
    SERVER_HOST=${PRODUCTION_HOST:-"event-app.com"}
    SERVER_USER=${PRODUCTION_USER:-"deploy"}
    SERVER_PATH=${PRODUCTION_PATH:-"/var/www/production"}
    ENV_FILE=".env.production"
fi

log "Configuration:"
log "  Serveur: $SERVER_USER@$SERVER_HOST"
log "  Chemin: $SERVER_PATH"
log "  Fichier env: $ENV_FILE"

# Vérification des prérequis
log "Vérification des prérequis..."

if ! command -v rsync &> /dev/null; then
    error "rsync n'est pas installé"
fi

if ! command -v ssh &> /dev/null; then
    error "ssh n'est pas installé"
fi

# Test de connexion SSH
log "Test de connexion SSH..."
if ! ssh -o ConnectTimeout=10 -o BatchMode=yes "$SERVER_USER@$SERVER_HOST" exit; then
    error "Impossible de se connecter au serveur"
fi

# Sauvegarde de l'environnement actuel
log "Création d'une sauvegarde..."
ssh "$SERVER_USER@$SERVER_HOST" "
    if [ -d '$SERVER_PATH' ]; then
        cp -r '$SERVER_PATH' '${SERVER_PATH}_backup_$TIMESTAMP'
        echo 'Sauvegarde créée: ${SERVER_PATH}_backup_$TIMESTAMP'
    fi
"

# Installation des dépendances localement
log "Installation des dépendances..."
composer install --no-dev --optimize-autoloader --no-interaction
npm ci
npm run build

# Synchronisation des fichiers
log "Synchronisation des fichiers..."
rsync -avz --delete \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='tests' \
    --exclude='.github' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.env*' \
    --exclude='phpunit.xml' \
    --exclude='README.md' \
    --exclude='scripts' \
    ./ "$SERVER_USER@$SERVER_HOST:$SERVER_PATH/"

# Configuration de l'environnement sur le serveur
log "Configuration de l'environnement sur le serveur..."
ssh "$SERVER_USER@$SERVER_HOST" "
    cd '$SERVER_PATH'
    
    # Copier le fichier d'environnement approprié
    if [ -f '$ENV_FILE' ]; then
        cp '$ENV_FILE' .env
    else
        echo 'ATTENTION: Fichier $ENV_FILE non trouvé'
    fi
    
    # Installer les dépendances Composer
    composer install --no-dev --optimize-autoloader --no-interaction
    
    # Optimisations Laravel
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    
    # Migrations de base de données
    php artisan migrate --force
    
    # Créer le lien symbolique pour le storage
    php artisan storage:link
    
    # Permissions
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    
    # Redémarrer les services
    sudo systemctl reload php8.3-fpm
    sudo systemctl reload nginx
"

# Vérification du déploiement
log "Vérification du déploiement..."
if curl -f -s "$SERVER_HOST" > /dev/null; then
    log "✅ Déploiement réussi! L'application est accessible."
else
    warning "⚠️  L'application ne semble pas accessible. Vérifiez la configuration."
fi

# Nettoyage des anciennes sauvegardes (garder les 5 dernières)
log "Nettoyage des anciennes sauvegardes..."
ssh "$SERVER_USER@$SERVER_HOST" "
    cd '$(dirname $SERVER_PATH)'
    ls -dt ${SERVER_PATH}_backup_* 2>/dev/null | tail -n +6 | xargs rm -rf
"

log "🎉 Déploiement terminé avec succès!"
log "📝 Sauvegarde disponible: ${SERVER_PATH}_backup_$TIMESTAMP"
