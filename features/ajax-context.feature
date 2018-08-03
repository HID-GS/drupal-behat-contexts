@javascript @ajax
Feature: Test AjaxContext
  In order to prove the steps defined in the AjaxContext are working properly
  As a developer
  I need to use the step definitions of this context

  Scenario: Test if HID Global's drivers search is working
    Given I am on "https://www.hidglobal.com/drivers"
    When I fill in "Search by product or keyword..." with "LINUX 32 BIT"
    And I wait for AJAX to start
    And I wait for AJAX to finish
    Then I should see the text "LINUX 32 BIT"