#!/usr/bin/env bash
set -e

echo "Running Render deployment start script..."

# Cache configuration, routes, and views for production performance
echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

# Run database migrations automatically
# The --force flag is required in production environments to bypass confirmation prompts
echo "Running database migrations..."
php artisan migrate --force

echo "Starting queue worker in the background..."
php artisan queue:work --tries=3 --timeout=150 &

echo "Starting Apache..."
# Execute Apache in the foreground
exec apache2-foreground
