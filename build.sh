#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "🚀 Starting Laravel deployment build..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configuration
echo "⚙️  Optimizing configuration..."
php artisan config:clear
php artisan config:cache

# Clear and cache routes
echo "🛣️  Optimizing routes..."
php artisan route:clear
php artisan route:cache

# Clear and cache views
echo "👁️  Optimizing views..."
php artisan view:clear
php artisan view:cache

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database with initial roles and users
echo "🌱 Seeding database..."
php artisan db:seed --class=RoleUserSeeder --force

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Build completed successfully!"

