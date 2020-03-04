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
