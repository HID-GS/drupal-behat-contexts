<?php

namespace HidGlobal\DrupalBehatContexts\Context;

use Behat\Behat\Context\Context;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Provides pre-built step definitions for interacting with AJAX.
 */
class AjaxContext extends RawDrupalContext implements Context {

  /**
   * Wait for AJAX to start.
   *
   * Helpful when a timeout delays an Ajax request (via jQuery).
   *
   * @Then I wait for AJAX to start
   */
  public function iWaitForAjaxToStart() {
    $time = 5000;
    $this->getSession()->wait($time, '(jQuery.active !== 0)');
  }

}
