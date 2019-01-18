@api @cookie
Feature: Test CookieContext
  In order to prove the steps defined in the CookieContext are working properly
  As a developer
  I need to use the step definitions of this context

  Background:
    Given I am on the homepage

  Scenario: Test for cookies that aren't set
    Then I should not have the cookie "behat"

  Scenario: Test that cookies can be set
    Given I have a cookie named "behat" with the value "cookie1"
    Then I should have the cookie "behat"
    And the value of cookie "behat" should be "cookie1"
