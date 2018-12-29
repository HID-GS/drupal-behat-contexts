# Contributing

Below is a quick guide on how to write and test your own steps.

## Adding step definitions

You can define custom steps with Behat's Context class. The process is as follows:

1. Create a **.php** file in */src/Context* so it's loadable by Behat
2. Create a POPO (Plain Old PHP Object) that implements `Context` and extends `RawDrupalContext`
3. Using php-doc annotations, write out the text step pattern ([more information](http://behat.org/en/latest/user_guide/context/definitions.html#creating-your-first-step-definition))
4. Update `behat.yml.dist` with your context class
5. Create a *.feature file to test your step (see the `features` folder)

Boilerplate:

```php
<?php

namespace Hid\DrupalBehatContexts\Context;

use Behat\Behat\Context\Context;
use Drupal\DrupalExtension\Context\RawDrupalContext;

class YourContext extends RawDrupalContext implements Context {

  /**
   * @When there is/are :count monster(s)
   */
  public function thereAreMonsters($count) {}

}
```

## Testing

### PHP Code Sniffer

This repo adheres to Drupal's PHPCS rulesets.  To test your code, run the following:

`make cs-check` or `vendor/bin/phpcs`

If there are issues, PHPCS might be able to autocorrect for you.

`make cs-fix` or `vendor/bin/phpcbf`

### Behat

Your custom step definitions should be tested in a feature. Since testing a feature requires a Drupal installation, this repo includes a Drupal stack that you can build if Docker is installed on your machine. To run all Behat tests:

```
make behat
```

This will do four things:

1. Run `composer install` if not already done
2. Start Drupal
3. Install Drupal if not already done
4. Execute all tests in the `features` folder

The site will be running at [http://localhost:8080](http://localhost:8080) and Selenium will be accessible at [http://localhost:4444/wd/hub](http://localhost:4444/wd/hub).

Alternately, you can run `make test` to run tests for both PHPCS and Behat.

When you have finished testing, stop all containers with:

```bash
make stop
```

### Drupal Installation Profile

The `d8_profile` can be used to enable modules, create entities and setup necessary configuration so that new step definitions can be tested in Behat features.

Let's say you want to write a step definition for a contrib module that adds a new field type. Eventually, you want to update the `d8_profile` with the contrib module and attach the field type to an entity. That way your step definition can be tested in a feature.

The process to update the profile would be similar to this:

1. Add the contrib module to `d8_profile.info.yml` and `d8_profile.make`.
2. Restart the Drupal stack (`make clean` then `make start`) to confirm that the contrib module is downloaded and installed.
3. Login to Drupal and add the field to an existing entity or to a new entity that you create.
4. [Export site configuration changes](https://www.drupal.org/docs/8/configuration-management/managing-your-sites-configuration) and copy the config files to the profile's `config/install` directory.
5. Remove `core.extension.yml` and UUIDs using the commands from the [Drupal documentation](https://www.drupal.org/node/2210443).
6. Restart the Drupal stack again. You should now be able to test the new field in a feature.
