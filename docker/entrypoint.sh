#!/bin/sh
set -e

echo "=== Fixing storage permission (runtime) ==="

mkdir -p /var/www/storage/app/public
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage

exec "$@"
