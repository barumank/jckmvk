#!/bin/bash

CUR_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
source ${CUR_DIR}/../.env
CONTAINER_NAME="${COMPOSE_PROJECT_NAME}_php-cli_1"

docker exec -it ${CONTAINER_NAME} bash -c 'cd /var/www/mvk_crm/backend && composer update'