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