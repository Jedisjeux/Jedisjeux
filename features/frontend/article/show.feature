@ui @frontend @article @show
Feature: View articles
  In order to manage articles
  As a visitor
  I need to be able to view a article

  Background:
    Given there are users:
      | email             |
      | kevin@example.com |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                   | author            |
      | articles/actualites | Critique de Vroom Vroom | kevin@example.com |

  Scenario: View an article
    Given I am on "/articles/"
    When I follow "Critique de Vroom Vroom"
    Then I should see "Critique de Vroom Vroom"