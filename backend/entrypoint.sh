#!/bin/sh

# Esperar a que MySQL esté listo

echo "MySQL está listo, continuando con la ejecución..."

# Crear la base de datos si no existe
php bin/console doctrine:database:create --if-not-exists


# Ejecutar las migraciones
php bin/console doctrine:migrations:migrate --no-interaction

# Actualizar el esquema de la base de datos
php bin/console doctrine:schema:update --complete --force

# Iniciar PHP-FPM
php-fpm
