FROM drupal:8-apache

RUN pecl install xdebug-2.6.0 \
	  && docker-php-ext-enable xdebug
