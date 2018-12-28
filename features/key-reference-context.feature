@api @key-reference
Feature: Test KeyReferenceContext
  In order to prove the steps defined in the KeyReferenceContext are working properly
  As a developer
  I need to use the step definitions of this context

  Background: Test that keyed entities can be created
    Given I am logged in as an "administrator"
    And I create "taxonomy_term" of type "tags":
      | KEY     | name    |
      | tags_01 | Tags 01 |
      | tags_02 | Tags 02 |
      | tags_03 | Tags 03 |
    And I create "article" content:
      | KEY          | title        | field_tags                |
      | article_node | Article node | tags_01, tags_02, tags_03 |
    And I create large "paragraph" of type "content":
      | KEY                  | content_paragraph |
      | field_prgf_header    | Behat rocks       |
      | field_prgf_link:uri  | http://behat.org  |
      | :title               | Behat             |
      | field_prgf_reference | article_node      |
      | field_prgf_email     | behat@example.com |

  Scenario: Test that generated keyed entities can be referenced and viewed
    When I create large "page" content:
      | KEY              | page_node         |
      | title            | Page node         |
      | body             | Body text...      |
      | field_paragraphs | content_paragraph |
    And I view node "page_node"
    Then I should see the heading "Page node"
    And I should see "Body text..."
    And I should see the link "Behat"
    And I should see "Article node"
