#!/usr/bin/env sh

## Installs site via drush.
##
## Usage: ./scripts/site-install.sh

docker-compose run drush \
               si -y standard \
               --root=/var/www/html \
               --db-url=mysql://drupal:drupal@db/drupal \
               --site-name="Drupal Behat Contexts" \
               install_configure_form.enable_update_status_module=NULL

# Fix permissions on sites/default
docker-compose exec drupal sh -c "chown -R www-data:www-data /var/www/html/sites/default"
