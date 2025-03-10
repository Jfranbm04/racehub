# Usar la imagen oficial de PHP con Apache
FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  && docker-php-ext-install pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Cambiar el usuario www-data para que tenga UID y GID específicos
RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

# Copiar los archivos de la aplicación y asignar los permisos correctos
COPY --chown=www-data:www-data . /var/www/html/

# Cambiar los permisos para los archivos y directorios públicos
RUN chmod -R 755 /var/www/html/public

# Establecer el usuario a www-data para la ejecución de la aplicación
USER root

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de Composer (evitar usar cache de Composer)
RUN composer install --no-cache

# Si necesitas agregar más dependencias
RUN composer require symfony/orm-pack doctrine/doctrine-bundle
RUN composer require doctrine/dbal
RUN composer require nelmio/cors-bundle

# Establecer permisos para el directorio var
RUN chmod -R 777 /var/www/html/var

# Copiar el entrypoint y darle permisos de ejecución
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto
EXPOSE 9000

# Usar el script como punto de entrada (descomentar si deseas usarlo)
# ENTRYPOINT ["/entrypoint.sh"]
