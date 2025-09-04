#!/usr/bin/env bash
set -e

cd /var/www/html

echo "Starting Laravel deployment script..."

echo "installing npm packages"
npm install

echo "Running npm build script..."
npm run build

echo "Running composer"
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Clearing old caches..."
php artisan cache:clear

echo "Running migrations..."
php artisan migrate --force

echo "Deployment complete."
