FROM nginx:latest

# Install necessary packages
RUN apt-get update && apt-get install -y \
    openssl \
    && rm -rf /var/lib/apt/lists/*

# Create directory for PHP socket
RUN mkdir -p /var/run/php

# Create directory for logs
RUN mkdir -p /var/log/nginx

# Set proper permissions
RUN chown -R nginx:nginx /var/log/nginx
RUN chown -R nginx:nginx /var/www/html

# Copy Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Create cache directories and set permissions
RUN mkdir -p /var/cache/nginx && \
    chown -R nginx:nginx /var/cache/nginx && \
    chmod -R 755 /var/cache/nginx

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]