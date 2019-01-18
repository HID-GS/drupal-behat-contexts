<?php

namespace HidGlobal\DrupalBehatContexts\Context;

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\Context;

/**
 * Provides pre-built step definitions for interacting with HTTP cookies.
 */
class CookieContext extends RawDrupalContext implements Context {

  /**
   * Sets a cookie.
   *
   * @param string $name
   *   Name of cookie.
   * @param string $value
   *   Value of cookie.
   *
   * @Given I have a cookie named :name with the value :value
   */
  public function iHaveCookieWithTheValue($name, $value) {
    $this->getSession()->setCookie($name, $value);
  }

  /**
   * Checks if a specified cookie exists.
   *
   * @param string $name
   *   Name of cookie.
   *
   * @Then I should have the cookie :name
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function iShouldHaveTheCookie($name) {
    $this->assertSession()->cookieExists($name);
  }

  /**
   * Checks if a specified cookie does not exist.
   *
   * @param string $name
   *   Name of cookie.
   *
   * @Then I should not have the cookie :name
   *
   * @throws \Exception
   */
  public function iShouldNotHaveTheCookie($name) {
    $cookie = $this->getSession()->getCookie($name);

    if (isset($cookie)) {
      throw new \Exception(
        'The cookie ' . $name . ' was found, but it should not be.'
      );
    }
  }

  /**
   * Checks the value of a specified cookie.
   *
   * @param string $name
   *   Name of cookie.
   * @param string $value
   *   Value of cookie.
   *
   * @Then the value of cookie :name should be :value
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function theValueOfCookieShouldBe($name, $value) {
    $this->assertSession()->cookieEquals($name, $value);
  }

}
