FROM temtest/phalcon:7.2-fpm-phalcon3.4-alpine

RUN apk add imagemagick  imagemagick-libs imagemagick-dev \
    && pecl install imagick \
    && pecl install xdebug \
	&& pecl install redis-4.3.0 \
#	&& pecl install swoole-4.3.1 \
	&& docker-php-ext-enable xdebug redis imagick \
	&& docker-php-ext-install sockets \
\
	#Cleaning up
	&& apk del .build-deps && rm -rf /tmp/* \
\
	#Config
	&& mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && rm -rf /var/cache/apk/*

# Add a non-root user to prevent files being created with root permissions on host machine.
ARG USER_ID=1000
ARG USER_GROUP_ID=1000
ENV USER_ID="${USER_ID}"
ENV USER_GROUP_ID="${USER_GROUP_ID}"

RUN addgroup -g $USER_ID -S docker \
   && adduser -u $USER_GROUP_ID -S docker -G docker
USER docker

