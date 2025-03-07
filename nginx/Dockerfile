FROM racehub-api as api

FROM nginx:latest

# Install required packages
RUN apt-get update && apt-get install -y \
    openssl \
    && rm -rf /var/lib/apt/lists/*

# Create necessary directories
RUN mkdir -p /var/run/php && \
    mkdir -p /var/log/nginx && \
    mkdir -p /var/www/html && \
    mkdir -p /var/cache/nginx

# Set proper permissions
RUN chown -R nginx:nginx /var/log/nginx && \
    chown -R nginx:nginx /var/www/html && \
    chown -R nginx:nginx /var/cache/nginx && \
    chmod -R 755 /var/cache/nginx

# Copy configuration from api container
COPY --from=api /var/www/html /var/www/html

# Copy Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Ensure proper permissions for PHP-FPM socket directory
RUN chown -R nginx:nginx /var/run/php && \
    chmod -R 755 /var/run/php

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
