#!/usr/bin/env bash
set -xe

echo "Starting Yoklmn (debug mode = $APP_DEBUG)..."

if ! grep -q "APP_KEY" .env 2>/dev/null; then
    echo "App key missing, generating..."
    php artisan key:generate
fi

if [ "$APP_DEBUG" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force

    echo "Running Vite development server..."
    npm run dev >/proc/1/fd/1 2>/proc/1/fd/2 &
    disown
fi

if [ "$APP_SEED" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed
fi

exec "$@"
