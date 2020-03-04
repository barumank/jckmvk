FROM temtest/phalcon:7.2-cli-phalcon3.4-alpine

ENV PHALCON_DEV_TOOLS=3.4.1

RUN \
    apk add imagemagick  imagemagick-libs imagemagick-dev \
    #Installing Phalcon dev tools
	&& wget https://github.com/phalcon/phalcon-devtools/archive/v${PHALCON_DEV_TOOLS}.tar.gz -O - | tar -zx -C /tmp/ \
    && mv /tmp/phalcon-devtools-${PHALCON_DEV_TOOLS} /usr/src/phalcon-devtools \
    && ln -sf /usr/src/phalcon-devtools/phalcon.php /usr/local/bin/phalcon \

    && pecl install imagick \
	&& pecl install xdebug \
	&& pecl install redis-4.3.0 \
#	&& pecl install swoole-4.3.1 \
	&& docker-php-ext-enable xdebug redis imagick \
	&& docker-php-ext-install sockets \
	#####################################
    # Инструменты сборки
    #####################################
	&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
\
	#Cleaning up
	&& apk del .build-deps && rm -rf /tmp/* \
\
	#Config
	&& mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
	&& rm -rf /var/cache/apk/*




#RUN apk add --no-cache git
#
#RUN    apk update \
#    && apk add openssh
#
#
## Copy SSH key for git private repos
#ADD id_rsa/bkpartner_rsa /root/.ssh/bkpartner_rsa
#RUN chmod 600 /root/.ssh/bkpartner_rsa


# Add a non-root user to prevent files being created with root permissions on host machine.
ARG USER_ID=1000
ARG USER_GROUP_ID=1000
ENV USER_ID="${USER_ID}"
ENV USER_GROUP_ID="${USER_GROUP_ID}"

RUN addgroup -g $USER_ID -S docker \
   && adduser -u $USER_GROUP_ID -S docker -G docker
USER docker

# Bash script for running process with infinity loop
COPY loop.sh loop.sh
CMD ["sh","loop.sh"]
