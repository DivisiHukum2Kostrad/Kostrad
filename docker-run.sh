#!/bin/sh
# Matikan error jika migrasi gagal (biar web tetap jalan)
set -e

# Jalankan migrasi database
echo "🛠️ Sedang menjalankan migrasi..."
php artisan migrate --force
# php artisan migrate:fresh --force

# (Opsional) Jalankan Seeder jika perlu data awal
php artisan db:seed --force

# Jalankan Apache (Server Web)
echo "🚀 Menjalankan Apache..."
apache2-foreground
