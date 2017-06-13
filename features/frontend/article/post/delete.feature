@ui @frontend @article @comment @delete
Feature: Remove article comment
  In order to comment articles
  As a user
  I need to be able to remove my comments

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                   | author            |
      | articles/actualites | Critique de Vroom Vroom | kevin@example.com |
    And article "Critique de Vroom Vroom" has following comments:
      | author            |
      | kevin@example.com |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Remove my comment
    Given I am on "/articles/"
    And I follow "Critique de Vroom Vroom"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove my comment with modal
    Given I am on "/articles/"
    And I follow "Critique de Vroom Vroom"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should see "a bien été supprimé"