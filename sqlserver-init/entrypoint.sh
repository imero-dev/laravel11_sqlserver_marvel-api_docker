#!/bin/bash

# Iniciar SQL Server en segundo plano
/opt/mssql/bin/sqlservr &

# Esperar a que SQL Server esté disponible
echo "Esperando a que SQL Server inicie..."
sleep 15s

# Ejecutar el script de inicialización
echo "Ejecutando el script de inicialización..."
/opt/mssql-tools18/bin/sqlcmd -C -S localhost -U sa -P "$SA_PASSWORD" -i /init-database.sql

# Mantener el contenedor en ejecución
wait
