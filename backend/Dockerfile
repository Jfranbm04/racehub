# Usar la imagen oficial de PHP con Apache
FROM php:8.2-fpm

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

# Establecer permisos para el directorio var
RUN chmod -R 777 /app/var

# Copiar el entrypoint y darle permisos de ejecución
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto
EXPOSE 9000

# Usar el script como punto de entrada
# ENTRYPOINT ["/entrypoint.sh"]
