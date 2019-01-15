<?php

namespace HidGlobal\DrupalBehatContexts\Context;

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\Context;

/**
 * Provides pre-built step definitions for interacting with HTTP cookies.
 */
class CookieContext extends RawDrupalContext implements Context {

  /**
   * Gets cookie by name.
   *
   * @param string $cookie_name
   *   Name of cookie.
   *
   * @return array
   *   Cookie attributes, such as name, value and expiration.
   *
   * @throws \Exception
   */
  protected function getCookieByName($cookie_name) {
    $driver = $this->getSession()->getDriver();
    $seleniumSession = $driver->getWebDriverSession();
    $cookies = $seleniumSession->getAllCookies();
    if (!is_null($cookies) && !empty($cookies) && is_array($cookies)) {
      foreach ($cookies as $cookie) {
        if (empty($cookie['name']) || $cookie['name'] !== $cookie_name) {
          continue;
        }
        return $cookie;
      }
    }
    $msg = 'Cookie ' . $cookie_name . ' was not found.';
    throw new \Exception($msg);
  }

  /**
   * Checks if session has cookie.
   *
   * @param string $cookie_name
   *   Name of cookie.
   *
   * @Given /^I should have the cookie "(?P<cookie_name>[^"]*)"$/
   *
   * @throws \Exception
   */
  public function iShouldHaveTheCookie($cookie_name) {
    $cookie = $this->getSession()->getCookie($cookie_name);
    if (is_null($cookie)) {
      $msg = 'Cookie ' . $cookie_name . ' was not found.';
      throw new \Exception($msg);
    }
  }

  /**
   * Checks if session does not have cookie.
   *
   * @param string $cookie_name
   *   Name of cookie.
   *
   * @Given /^I should not have the cookie "(?P<cookie_name>[^"]*)"$/
   *
   * @throws \Exception
   */
  public function iShouldNotHaveTheCookie($cookie_name) {
    $cookie = $this->getSession()->getCookie($cookie_name);
    if (!is_null($cookie)) {
      $msg = 'Cookie ' . $cookie_name . ' was not found.';
      throw new \Exception($msg);
    }
  }

  /**
   * Checks the expiration date of a cookie.
   *
   * @param string $cookie_name
   *   Name of cookie.
   * @param string $days
   *   Number of days the cookie should expire.
   *
   * @Given /^The cookie "(?P<cookie_name>[^"]*)" has expiration (\d+) days from now$/
   *
   * @throws \Exception
   */
  public function theCookieHasExpirationDaysFromNow($cookie_name, $days) {
    $cookie = $this->getCookieByName($cookie_name);
    $cookie_param = 'expiry';
    if (!empty($cookie[$cookie_param])) {
      $year_from_today = strtotime('+' . $days . 'days');
      $year_from_yesterday = strtotime('+' . $days - 1 . ' days');
      if (!($year_from_yesterday <= $cookie[$cookie_param] && $cookie[$cookie_param] <= $year_from_today)) {
        $expire_date_time = date('Y/m/d H:i:s', $cookie[$cookie_param]);
        $msg = 'Cookie ' . $cookie_name . ' does not expire in ' . $days . ', it is set to expire on ' . $expire_date_time . '.';
        throw new \Exception($msg);
      }
    }
    else {
      $msg = 'Cookie ' . $cookie_name . ' does not expire.';
      throw new \Exception($msg);
    }
  }

  /**
   * Checks if cookie is httpOnly.
   *
   * @param string $cookie_name
   *   Name of cookie.
   * @param string $bool_string
   *   Denotes if cookie should be secure.
   *
   * @Given /^The cookie "(?P<cookie_name>[^"]*)" httpOnly is "(?P<boolean_string>[^"]*)"$/
   *
   * @throws \Exception
   */
  public function theCookieHttpOnlyIs($cookie_name, $bool_string) {
    $bool = filter_var($bool_string, FILTER_VALIDATE_BOOLEAN);
    $cookie = $this->getCookieByName($cookie_name);
    $cookie_param = 'httpOnly';
    if (isset($cookie[$cookie_param])) {
      if ($cookie[$cookie_param] !== $bool) {
        $httpOnly = $cookie[$cookie_param] ? 'True' : 'False';
        $msg = 'Cookie ' . $cookie_name . ' "httpOnly" value is ' . $httpOnly . '.';
        throw new \Exception($msg);
      }
    }
    else {
      $msg = 'Cookie ' . $cookie_name . ' "httpOnly" is missing.';
      throw new \Exception($msg);
    }
  }

  /**
   * Checks if cookie is secure.
   *
   * @param string $cookie_name
   *   Name of cookie.
   * @param string $bool_string
   *   Denotes if cookie should be secure.
   *
   * @Given /^The cookie "(?P<cookie_name>[^"]*)" secure is "(?P<boolean_string>[^"]*)"$/
   *
   * @throws \Exception
   */
  public function theCookieSecureIs($cookie_name, $bool_string) {
    $bool = filter_var($bool_string, FILTER_VALIDATE_BOOLEAN);
    $cookie = $this->getCookieByName($cookie_name);
    $cookie_param = 'secure';
    if (isset($cookie[$cookie_param])) {
      if ($cookie[$cookie_param] !== $bool) {
        $secure = $cookie[$cookie_param] ? 'True' : 'False';
        $msg = 'Cookie ' . $cookie_name . ' "secure" value is ' . $secure . '.';
        throw new \Exception($msg);
      }
    }
    else {
      $msg = 'Cookie ' . $cookie_name . ' "secure" is missing.';
      throw new \Exception($msg);
    }
  }

  /**
   * Checks a cookie value for an ID.
   *
   * @param string $cookie_name
   *   Name of cookie.
   * @param string $key
   *   Key string.
   *
   * @Given /^The cookie "(?P<cookie_name>[^"]*)" value is the id of "(?P<key>[^"]*)"$/
   *
   * @throws \Exception
   */
  public function theCookieValueIsTheIdOf($cookie_name, $key) {
    $cookie = $this->getCookieByName($cookie_name);
    $id = $this->getEntityIdByKey($key);
    $cookie_param = 'value';
    if (!empty($cookie[$cookie_param])) {
      if ($cookie[$cookie_param] !== $id) {
        $msg = 'Cookie ' . $cookie_name . ' "value" value is ' . $cookie[$cookie_param] . '.';
        throw new \Exception($msg);
      }
    }
    else {
      $msg = 'Cookie ' . $cookie_name . ' "value" is missing.';
      throw new \Exception($msg);
    }
  }

}
