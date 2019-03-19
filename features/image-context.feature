@api @image
Feature: Test ImageContext
  In order to prove the steps defined in the ImageContext are working properly
  As a developer
  I need to use the step definitions of this context

  Scenario: Test that an image loads when creating an article
    When I am viewing an "article":
      | title       | Behat rocks                            |
      | field_image | http://via.placeholder.com/300x300.jpg |
    Then I should receive all images in the ".node__content" element