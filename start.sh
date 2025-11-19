#!/bin/bash
set -e

# Use PORT env var or default to 8080
export LISTEN_PORT=${PORT:-8080}

# Update nginx config with actual port
sed -i "s/listen 8080;/listen $LISTEN_PORT;/" /etc/nginx/sites-available/default

# Ensure proper permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Start PHP-FPM
php-fpm -D

# Wait for PHP-FPM to start
sleep 2

# Clear any existing cache
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true

# Run migrations
php artisan migrate --force

# Run seeders (only first time, comment out after)
php artisan db:seed --class=RoleUserSeeder --force

# Cache config and routes
php artisan config:cache
php artisan route:cache

# Display Laravel logs in real-time
tail -f /var/www/storage/logs/laravel.log &

# Start Nginx
nginx -g 'daemon off;'
