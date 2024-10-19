#!/bin/bash

# Esperar a que el servicio de SQL Server esté disponible
echo "Esperando a que SQL Server esté disponible..."
until nc -z -v -w30 sqlserver 1433
do
  echo "Esperando a SQL Server..."
  sleep 5
done
echo "SQL Server está disponible."

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Iniciar PHP-FPM
echo "Iniciando PHP-FPM..."
php-fpm
