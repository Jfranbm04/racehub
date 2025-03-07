#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

echo "Making sure public / private keys for JWT exist..."
php bin/console lexik:jwt:generate-keypair --skip-if-exists --no-interaction

# Clear cache with proper permissions before starting
echo "Clearing cache..."
rm -rf var/cache/*
mkdir -p var/cache
chmod -R 777 var/cache

echo "Waiting for database to be ready..."
ATTEMPTS_LEFT_TO_REACH_DATABASE=60
until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
    if [ $? -eq 255 ]; then
        # If the Doctrine command exits with 255, an unrecoverable error occurred
        ATTEMPTS_LEFT_TO_REACH_DATABASE=0
        break
    fi
    sleep 1
    ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
    echo "Still waiting for database to be ready... Or maybe the database is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
done

if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
    echo "The database is not up or not reachable:"
    echo "$DATABASE_ERROR"
    exit 1
else
    echo "The database is now ready and reachable"
fi

# Create database if not exists
php bin/console doctrine:database:create --if-not-exists || true

# Set JWT directory permissions
setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt || true
setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt || true

# Run migrations
# php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:schema:update --complete --force
# php bin/console doctrine:migrations:diff --no-interaction || true
# php bin/console doctrine:migrations:migrate --no-interaction || true

# Start PHP-FPM if it was requested
if [ "$1" = "php-fpm" ]; then
    exec "$@"
else
    # Start the PHP development server if no specific command was provided
    exec php -S 0.0.0.0:8000 -t public
fi
