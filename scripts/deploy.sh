#!/usr/bin/env bash
# Deployment helper for Laravel Cloud / generic Linux hosts
# Usage: run these commands during the deploy/post-deploy hook.

set -euo pipefail

echo "Running deploy script: create sqlite, generate key, migrate, set permissions"

# Ensure database folder exists and sqlite file is present
mkdir -p "$PWD/database"
if [ ! -f "$PWD/database/database.sqlite" ]; then
  echo "Creating database/database.sqlite"
  touch "$PWD/database/database.sqlite"
fi

# Generate app key if missing
if grep -q "APP_KEY=\s*$" .env || [ -z "$(php artisan tinker --execute='echo config("app.key");' 2>/dev/null)" ]; then
  echo "Generating APP_KEY"
  php artisan key:generate --force
fi

# Set correct permissions for storage and cache (useful on many hosts)
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

# Run migrations
echo "Running migrations"
php artisan migrate --force

# Optimize (optional)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Deploy script finished."
