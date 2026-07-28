#!/bin/bash

# Tunggu database siap
echo "Menunggu database..."
RETRIES=30
until php artisan db:show --no-interaction --quiet 2>/dev/null || [ $RETRIES -eq 0 ]; do
    sleep 2
    RETRIES=$((RETRIES-1))
done

if [ $RETRIES -eq 0 ]; then
    echo "Database tidak tersedia, melanjutkan..."
fi

# Generate APP_KEY jika belum diset (tanpa .env file)
if [ -z "$APP_KEY" ]; then
    APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    export APP_KEY
fi

# Storage link
php artisan storage:link --force 2>/dev/null || true

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Migrate & seed
php artisan migrate --force 2>&1 || echo "Migrasi gagal"
php artisan db:seed --force 2>&1 || echo "Seeder gagal"

# Cache
php artisan config:cache 2>&1 || echo "Config cache gagal"
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

# Jalankan Apache
exec apache2-foreground
