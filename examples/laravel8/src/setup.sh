#!/bin/bash
set -e

# Create SQLite database
touch /app/database/database.sqlite

# Set DB connection to sqlite in .env
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sed -i 's|^DB_DATABASE=.*|DB_DATABASE=/app/database/database.sqlite|' .env
sed -i '/^DB_HOST=/d' .env
sed -i '/^DB_PORT=/d' .env
sed -i '/^DB_USERNAME=/d' .env
sed -i '/^DB_PASSWORD=/d' .env

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

echo "[OK] Laravel setup complete"
