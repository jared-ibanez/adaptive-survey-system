#!/usr/bin/env bash
set -e

# Ensure correct permissions for Laravel writeable dirs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache || true

# (Optional but recommended) optimize caches for production
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Storage symlink (only if you use storage for uploads)
php artisan storage:link || true

# Run migrations (recommended for small/student apps; safe with --force)
php artisan migrate --force

# Start services
php-fpm -D
nginx -g "daemon off;"
