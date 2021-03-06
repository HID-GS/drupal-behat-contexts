DRUPAL_ROOT    := /var/www/html
DRUPAL_PROFILE := d8_profile

NO_COLOR := \x1b[0m
OK_COLOR := \x1b[33;11m

SUCCESS_MSG := $(OK_COLOR)** Drupal is up and running **$(NO_COLOR)

define check-install
	docker-compose run -T --rm drupal \
		vendor/bin/drush core-status \
			--root=$(DRUPAL_ROOT) \
			--fields=drupal-settings-file \
			--field-labels=0 \
  	| grep -q "settings.php"; echo $$?
endef

define install-drupal
	@echo "Starting Drupal installation..."
	@sleep 5
	@docker-compose exec drupal \
            	sh -c "cp -R profiles/$(DRUPAL_PROFILE) $(DRUPAL_ROOT)/profiles"
	@docker-compose exec drupal \
        	vendor/bin/drush make -y --no-core $(DRUPAL_ROOT)/profiles/$(DRUPAL_PROFILE)/$(DRUPAL_PROFILE).make $(DRUPAL_ROOT)
	@docker-compose exec drupal \
    	vendor/bin/drush si -y $(DRUPAL_PROFILE) \
        	--root=$(DRUPAL_ROOT) \
            --db-url=mysql://drupal:drupal@db/drupal \
            --site-name="Drupal Behat Contexts" \
            install_configure_form.enable_update_status_module=NULL
    @docker-compose exec drupal \
    	sh -c "chown -R www-data:www-data $(DRUPAL_ROOT)/sites/default"
    @echo "$(SUCCESS_MSG)"
endef

.PHONY: all cs-check cs-fix behat test start stop clean

all: vendor

vendor: composer.json composer.lock
	composer install

cs-check: vendor
	vendor/bin/phpcs

cs-fix: vendor
	vendor/bin/phpcbf

behat: vendor start
	@docker-compose exec -T drupal vendor/bin/behat -c behat.yml.dist

test: cs-check behat

start: vendor
	@docker-compose up -d
	$(eval install := $(shell $(check-install)))
	$(if $(filter-out $(install), 0), $(call install-drupal), @echo "$(SUCCESS_MSG)")

stop:
	@docker-compose down

clean:
	rm -rf vendor/
	docker-compose down -v