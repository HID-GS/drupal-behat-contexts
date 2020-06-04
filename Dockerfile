FROM drupal:8-apache

RUN apt-get update && apt-get install --no-install-recommends -y \
    mysql-client

RUN pecl install xdebug-2.8.0 \
	  && docker-php-ext-enable xdebug \
	  && docker-php-ext-install pdo_mysql
