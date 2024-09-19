FROM php:8.1-cli

RUN apt -y update && \
    apt install -y git zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
