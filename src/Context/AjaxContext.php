<?php

namespace Hid\DrupalBehatContexts\Context;

use Behat\Behat\Context\Context;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines Ajax scenarios from the specific context.
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
