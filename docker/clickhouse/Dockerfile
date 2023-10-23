FROM clickhouse/clickhouse-server:22.2-alpine

COPY ./*.sql /docker-entrypoint-initdb.d

ARG MARIADB_HOST
ARG MARIADB_PORT
ARG MARIADB_DATABASE
ARG MARIADB_USER
ARG MARIADB_PASSWORD

RUN sed -i "s#{{user}}#${MARIADB_USER}#g" /docker-entrypoint-initdb.d/01_database.sql \
    && sed -i "s#{{pass}}#${MARIADB_PASSWORD}#g" /docker-entrypoint-initdb.d/01_database.sql \
    && sed -i "s#{{db}}#${MARIADB_DATABASE}#g" /docker-entrypoint-initdb.d/01_database.sql \
    && sed -i "s#{{host}}#${MARIADB_HOST}:${MARIADB_PORT}#g" /docker-entrypoint-initdb.d/01_database.sql

EXPOSE 9004
