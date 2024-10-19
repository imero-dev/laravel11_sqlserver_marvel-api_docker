FROM mcr.microsoft.com/mssql/server:2022-latest

# Establecer variables de entorno necesarias
ENV ACCEPT_EULA=Y \
    SA_PASSWORD=${SA_PASSWORD} \
    MSSQL_PID=Express

# Copiar el script de inicialización al contenedor
COPY init-database.sql /init-database.sql

# Copiar el script de entrada personalizado
COPY entrypoint.sh /entrypoint.sh

# Cambiar a root para modificar los permisos
USER root
RUN chmod +x /entrypoint.sh

# Volver a cambiar al usuario mssql
USER mssql

# Establecer el script de entrada personalizado como comando de inicio
CMD ["/bin/bash", "/entrypoint.sh"]
