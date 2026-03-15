#!/bin/bash
set -e

echo "========================================"
echo " Uniqa Inventory - Container Startup"
echo "========================================"

# Tunggu database benar-benar siap (double check setelah healthcheck)
echo "[1/6] Menunggu koneksi database..."
until php artisan db:show > /dev/null 2>&1; do
    echo "      Database belum siap, mencoba lagi dalam 3 detik..."
    sleep 3
done
echo "      Database OK."

# Cache config, route, view untuk performance
echo "[2/6] Meng-cache konfigurasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi database
echo "[3/6] Menjalankan migrasi database..."
php artisan migrate --force

# Buat symbolic link storage
echo "[4/6] Membuat storage symlink..."
php artisan storage:link --quiet 2>/dev/null || true

# Optimasi autoloader (sudah dilakukan saat build, ini sebagai fallback)
echo "[5/6] Optimasi autoloader..."
composer dump-autoload --optimize --quiet 2>/dev/null || true

echo "[6/6] Startup selesai. Menjalankan PHP-FPM..."
echo "========================================"

# Jalankan perintah utama (php-fpm atau queue:work, dll.)
exec "$@"
