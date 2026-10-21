#!/bin/sh
set -e

# Default PORT if not provided by Render
PORT=${PORT:-8080}
export PORT

echo "==> Configuring Nginx port to ${PORT}..."
envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

# SQLite database setup if using sqlite
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" ]; then
    echo "==> Setting up SQLite database..."
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chown -R www-data:www-data /var/www/html/database
fi

# Set proper permissions for storage & bootstrap cache
echo "==> Setting file permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate app key if not set
if [ -z "${APP_KEY}" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations and seeders safely
echo "==> Running database migrations..."
php artisan migrate --force --seed || echo "Migration warning: could not run migrate/seed automatically."

# Optimize caches for production
echo "==> Caching Laravel configuration and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Starting PHP-FPM and Nginx..."
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
