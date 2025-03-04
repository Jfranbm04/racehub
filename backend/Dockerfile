# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libicu-dev \
  && docker-php-ext-install pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar archivos del backend al contenedor
COPY . .

# Instalar dependencias con Composer
RUN composer install
RUN composer require symfony/orm-pack doctrine/doctrine-bundle
RUN composer require doctrine/dbal

# Ejecutar migraciones al construir la imagen
RUN php bin/console doctrine:migrations:diff || true
RUN php bin/console doctrine:migrations:migrate --no-interaction || true

# Establecer permisos para el directorio var
RUN chmod -R 777 /app/var

# Exponer el puerto
EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]