@api @image
Feature: Test ImageContext
  In order to prove the steps defined in the ImageContext are working properly
  As a developer
  I need to use the step definitions of this context

  Scenario: Test that an image loads when creating an article
    Given I am logged in as an "administrator"
    When I create "article" content:
      | KEY          | title        | field_image |
      | article_node | Article node | 200x200.png |
    And I view node "article_node"
    Then I should receive all images in the ".node__content" element
    And I should see the image "200x200.png"