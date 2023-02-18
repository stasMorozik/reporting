FROM php:latest-cli
FROM composer:latest

WORKDIR /reporting_app

COPY . .

RUN docker-php-ext-install pdo mysqli pdo_mysql
RUN COMPOSER_VENDOR_DIR="/vendor" composer install

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "./web", "./web/index.php"]

