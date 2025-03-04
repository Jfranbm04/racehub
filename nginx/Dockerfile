FROM nginx:latest

# Install openssl 
RUN apt-get update && apt-get install -y openssl && rm -rf /var/lib/apt/lists/*

# Create directory for PHP socket
RUN mkdir -p /var/run/php

# Copy Nginx configuration 
COPY default.conf /etc/nginx/conf.d/default.conf