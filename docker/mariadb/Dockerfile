FROM mariadb:11.1.2-jammy

COPY ./*.sql /docker-entrypoint-initdb.d

ARG APP_USER
ARG APP_UID
ARG APP_GID

RUN addgroup --gid ${APP_GID} ${APP_USER} && \
    adduser --gid ${APP_GID} --uid ${APP_UID} --home "/home/${APP_USER}"  ${APP_USER}

ARG PS1_EX
ENV PS1_EX $PS1_EX

RUN echo "export PS1=\"${PS1_EX}\"" >> "/home/${USER}/.bashrc" \
    && mkdir /home/${APP_USER}/scripts

USER ${APP_USER}
