#!/usr/bin/env sh
set -eu
cd "$(dirname "$0")/.."
composer install --no-interaction
[ -f .env ] || cp .env.example .env
[ -f database/database.sqlite ] || touch database/database.sqlite
if ! grep -q '^APP_KEY=base64:' .env; then php artisan key:generate; fi
php artisan migrate
php artisan db:seed
echo 'Ejecuta: php artisan aurea:admin y después php artisan serve'
