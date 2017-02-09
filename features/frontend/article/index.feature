@ui @frontend @article @index
Feature: View list of articles
  In order to manage articles
  As a visitor
  I need to be able to view all the articles

  Background:
    Given init doctrine phpcr repository
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                        |
      | articles/actualites | Critique de Vroom Vroom      |
      | articles/actualites | King of New York : Power Up! |

  Scenario: View list of articles
    When I am on "/articles/"
    Then I should see "Critique de Vroom Vroom"
    And I should see "King of New York : Power Up!"