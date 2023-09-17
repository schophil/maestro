FROM mariadb
ENV MYSQL_DATABASE maestrodb
ENV MYSQL_ROOT_PASSWORD maestro_root
ENV MYSQL_PASSWORD maestro
ENV MYSQL_USER maestro_user
ADD *.sql /docker-entrypoint-initdb.d/
