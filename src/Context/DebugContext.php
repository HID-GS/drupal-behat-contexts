<?php

namespace HidGlobal\DrupalBehatContexts\Context;

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;

/**
 * Provides useful debug tooling for step failures.
 */
class DebugContext extends RawDrupalContext implements Context {

  /**
   * Records HTML output of step failures to a file.
   *
   * @param \Behat\Behat\Hook\Scope\AfterStepScope $scope
   *   Behat's AfterStep hook scope.
   *
   * @AfterStep
   *
   * @throws \Behat\Mink\Exception\DriverException
   *   When the operation cannot be done.
   */
  public function recordHtmlAfterFailedStep(AfterStepScope $scope) {
    if ($scope->getTestResult()->getResultCode() === 99) {
      $html = $this->getSession()->getDriver()->getContent();
      $path = '/tmp/' . date('d-m-y') . '-' . uniqid() . '.txt';

      file_put_contents($path, $html);
      print 'Output printed at: ' . $path;
    }
  }

}
