FROM nginx:alpine

ARG APP_FASTCGI_PASS
ENV APP_FASTCGI_PASS ${APP_FASTCGI_PASS}

ARG APP_ROOT_PATH
ENV APP_ROOT_PATH ${APP_ROOT_PATH}

COPY ./site.conf /etc/nginx/conf.d/default.conf
RUN sed -i "s#{{fastcgi_pass}}#${APP_FASTCGI_PASS}#g" /etc/nginx/conf.d/default.conf \
    && sed -i "s#{{root_path}}#${APP_ROOT_PATH}#g" /etc/nginx/conf.d/default.conf
