# Drupal Behat Contexts

[![Build Status](https://travis-ci.org/HID-GS/drupal-behat-contexts.svg?branch=master)](https://travis-ci.org/HID-GS/drupal-behat-contexts)

Provides additional step definitions for testing Drupal sites using the [Behat Drupal Extension](https://www.drupal.org/project/drupalextension).

## Installation

Add the following to your `composer.json` file:

``` json
{
  "require-dev": {
    "hidgweb/drupal-behat-contexts": "^1.0"
  }
}
```

Then, update your dependencies by running `composer update hidgweb/drupal-behat-contexts`.

## Configuration

Once installed, add any of the contexts you want to use to your project's `behat.yml` file:

``` yaml
  default:
    suites:
      default:
        contexts:
          - HidGlobal\DrupalBehatContexts\Context\ImageContext
          - HidGlobal\DrupalBehatContexts\Context\AjaxContext
          - HidGlobal\DrupalBehatContexts\Context\KeyReferenceContext
          - HidGlobal\DrupalBehatContexts\Context\CookieContext
```

## Credits

KeyReferenceContext sourced from 
https://raw.githubusercontent.com/Kerby70/openy/8.x-1.x/tests/features/bootstrap/OpenyDrupalContext.php