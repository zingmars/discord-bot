FROM php:8.0-alpine

RUN apk add nodejs npm php8-opcache && rm -rf /var/cache/apk/*

ADD . / bot/
RUN rm -rf /bot/Docker /bot/vendor /bot/.env /bot/.git /bot/.idea /bot/.run /bot/docker-compose.yml /bot/.env.example /bot/.gitignore /bot/Lib/node* /bot/Data
RUN wget https://getcomposer.org/download/latest-stable/composer.phar -O /bot/composer.phar
RUN cd /bot/ && php composer.phar install && rm -rf /bot/composer.phar
RUN cd /bot/Lib && [ -f "./cleverbot-free/index.js" ] && npm install superagent md5

WORKDIR bot/
ENTRYPOINT [ "php", "/bot/index.php" ]

