FROM drupal:8-apache

RUN pecl install xdebug-2.8.0 \
	  && docker-php-ext-enable xdebug
