#!/usr/bin/env bash

CUR_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
source ${CUR_DIR}/../.env
CONTAINER_NAME="${COMPOSE_PROJECT_NAME}_node_1"

docker exec -it ${CONTAINER_NAME} bash -c "cd /var/www/mvk_crm/frontend/crm && ${@}"