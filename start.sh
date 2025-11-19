#!/bin/bash

# Use PORT env var or default to 8080
export LISTEN_PORT=${PORT:-8080}

# Update nginx config with actual port
sed -i "s/listen 8080;/listen $LISTEN_PORT;/" /etc/nginx/sites-available/default

# Start PHP-FPM
php-fpm -D

# Run migrations
php artisan migrate --force

# Run seeders (only first time, comment out after)
php artisan db:seed --class=RoleUserSeeder --force

# Cache config
php artisan config:cache
php artisan route:cache

# Start Nginx
nginx -g 'daemon off;'
