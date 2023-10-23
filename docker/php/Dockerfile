ARG PHP_VER
ARG PHP_SAPI
FROM php:${PHP_VER}-${PHP_SAPI}-alpine3.16 AS proto

RUN printf "#!/bin/sh\n" > /usr/bin/note \
    && chmod ugo+x /usr/bin/note

ARG PHP_SAPI
RUN echo "${PHP_SAPI}" > /var/sapi \
    && apk add --no-cache --virtual .build-deps \
    linux-headers $(note "needed by sockets")\
      $PHPIZE_DEPS \
    && docker-php-ext-install \
          pdo_mysql  \
          sockets \
    && if [ "${PHP_SAPI}" = "zts" ]; then docker-php-ext-install pcntl; fi \
    && pecl install apcu \
    && if [ "${PHP_SAPI}" = "zts" ]; then pecl install parallel; fi \
    && docker-php-ext-enable apcu \
    && if [ "${PHP_SAPI}" = "zts" ]; then docker-php-ext-enable parallel; fi \
    && apk del .build-deps

ARG APP_TIMEZONE
RUN apk add tzdata \
        && cp /usr/share/zoneinfo/${APP_TIMEZONE} /etc/localtime \
        && echo ${APP_TIMEZONE} > /etc/timezone \
        && apk del tzdata

ADD ./docker/php/ini/*ini ${PHP_INI_DIR}/conf.d/
RUN sed -i "s#{{date.timezone}}#${APP_TIMEZONE}#g" ${PHP_INI_DIR}/conf.d/date.ini

ADD ./docker/php/*sh /var/tools/
RUN chmod u+x /var/tools/*.sh && /var/tools/install-composer.sh


ARG APP_USER
ENV USER ${APP_USER}
ARG APP_UID
ENV UID ${APP_UID}
ARG APP_GID
ENV GID ${APP_GID}

RUN addgroup -g ${GID} -S ${USER} && \
    adduser -u ${UID} -S ${USER} -G ${USER}

COPY ./docker/php/.ashrc "/home/${APP_USER}/.ashrc"
ENV ENV="/home/${APP_USER}/.ashrc"

ARG APP_DIR
ARG APP_DIR_MAP

FROM proto AS prod-cli
COPY ${APP_DIR}/ ${APP_DIR_MAP}
RUN chown -R ${APP_USER}:${APP_USER} ${APP_DIR_MAP}
USER ${APP_USER}
WORKDIR ${APP_DIR_MAP}
USER ${APP_USER}
ARG PHP_SAPI
RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts \
    --optimize-autoloader --apcu-autoloader \
    $([ "$PHP_SAPI" == "fpm" ] && echo --ignore-platform-req=ext-parallel --ignore-platform-req=ext-pcntl) \
    && mkdir -p ./var/twig

FROM proto AS dev-cli
USER ${APP_USER}

FROM proto AS dev-fpm
USER ${APP_USER}

FROM proto AS prod-fpm
ARG APP_DIR
ARG APP_DIR_MAP
COPY --from=prod-cli ${APP_DIR_MAP}/ ${APP_DIR_MAP}
USER ${APP_USER}


FROM proto as dev-worker
RUN apk update && apk --no-cache add supervisor
ADD ./docker/php/worker/supervisord.conf /etc/supervisord.conf
ADD ./docker/php/worker/*.ini /etc/supervisor.d/
ARG WORKER_CMD
ARG WORKER_PROGRAM
RUN sed -i "s#{{program}}#${WORKER_PROGRAM}#g" /etc/supervisor.d/10_cmd.ini && \
    sed -i "s#{{cmd}}#${WORKER_CMD}#g" /etc/supervisor.d/10_cmd.ini && \
    sed -i "s#{{user}}#${APP_USER}#g" /etc/supervisor.d/10_cmd.ini
ENTRYPOINT ["supervisord", "--nodaemon"]


FROM dev-worker as prod-worker
ARG APP_DIR
ARG APP_DIR_MAP
COPY --from=prod-cli ${APP_DIR_MAP}/ ${APP_DIR_MAP}

