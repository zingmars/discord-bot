FROM php:8.0-alpine

RUN apk add nodejs && rm -rf /var/cache/apk/*

ADD . / bot/
RUN rm -rf /bot/Docker /bot/vendor /bot/.env /bot/.git /bot/.idea /bot/.run /bot/docker-compose.yml /bot/.env.example /bot/.gitignore
RUN wget https://getcomposer.org/download/latest-stable/composer.phar -O /bot/composer.phar
RUN cd /bot/ && php composer.phar install && rm -rf /bot/composer.phar

WORKDIR bot/
ENTRYPOINT [ "php", "/bot/index.php" ]

