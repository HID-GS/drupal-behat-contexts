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