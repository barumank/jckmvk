FROM node:12.13.0-alpine

RUN apk update && apk add bash

#ARG USER_ID=1000
#ARG USER_GROUP_ID=1000
#ENV USER_ID="${USER_ID}"
#ENV USER_GROUP_ID="${USER_GROUP_ID}"
#
#RUN addgroup -g $USER_GROUP_ID -S docker \
#   && adduser -u $USER_ID -S docker -G docker

USER node

# Bash script for running process with infinity loop
COPY loop.sh loop.sh
CMD ["sh","loop.sh"]