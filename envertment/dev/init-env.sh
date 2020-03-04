#!/bin/bash

# Текущая папка где лежит этот скрипт
# (чтобы не зависеть от той директории откуда его запускают)
if [ $(dirname $0) = "." ]
then
    CUR_DIR=$(pwd);
else
   CUR_DIR=$(dirname $0);
fi
cd ${CUR_DIR}

cp ./init-env/.env.tmpl .env
cp ./init-env/_connection.php ./../../backend/config/connection.php
cp ./init-env/_settings.json ./../../logger/settings.json

chmod 777 -R ./../../backend/cache

# get host ip for xdebug
HOST_IP=`docker network inspect bridge --format='{{(index .IPAM.Config 0).Gateway}}'`

sed -i -e "s#^\s*XDEBUG_REMOTE_HOST=.*#XDEBUG_REMOTE_HOST=${HOST_IP}#" .env
sed -i -e "s#^\s*XDEBUG_REMOTE_ENABLE=.*#XDEBUG_REMOTE_ENABLE=1#" .env
sed -i -e "s#^\s*XDEBUG_REMOTE_PORT=.*#XDEBUG_REMOTE_PORT=9000#" .env


