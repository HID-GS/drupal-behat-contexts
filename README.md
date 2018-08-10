# Drupal Behat Contexts

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
```
