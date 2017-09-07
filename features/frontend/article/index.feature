@ui @frontend @article @index
Feature: View list of articles
  In order to manage articles
  As a visitor
  I need to be able to view all the articles

  Background:
    Given init doctrine phpcr repository
    And there are users:
      | email             |
      | kevin@example.com |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code    | name       | parent   |
      | news    | Actualités | articles |
      | reviews | Critiques  | articles |
    And there are articles:
      | taxon               | title                        | author            |
      | articles/actualites | King of New York : Power Up! | kevin@example.com |
      | articles/critiques  | Critique de Vroom Vroom      | kevin@example.com |

  Scenario: View list of articles under a taxon
    Given I am on "/articles/"
    When I follow "Actualités"
    Then I should see "King of New York : Power Up!"
    But I should not see "Critique de Vroom Vroom"

  Scenario: Sorting articles
    Given I am on "/articles/"
    When I follow "Publié le"
    Then I should see "King of New York : Power Up!"
    When I follow "Les plus vus"
    Then I should see "King of New York : Power Up!"
    When I follow "Les plus commentés"
    Then I should see "King of New York : Power Up!"