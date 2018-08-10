DRUPAL_ROOT    := /var/www/html
DRUPAL_PROFILE := standard

NO_COLOR := \x1b[0m
OK_COLOR := \x1b[33;11m

SUCCESS_MSG := $(OK_COLOR)** Drupal is up and running **$(NO_COLOR)

define check-install
	docker-compose run -T --rm drush \
		status bootstrap \
			--root=$(DRUPAL_ROOT) \
  	| grep -q Successful; echo $$?
endef

define install-drupal
	@echo "Starting Drupal installation..."
	@sleep 5
	@docker-compose run --rm --no-deps drush \
    	si -y standard \
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