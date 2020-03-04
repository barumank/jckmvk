FROM golang:1.12.7-alpine3.9

RUN  apk add --update \
               bash \
               git \
  && apk add --no-cache \
             libc6-compat \
  && rm -rf /var/cache/apk/*

EXPOSE 8088

COPY run.sh run.sh
CMD ["sh","run.sh"]
