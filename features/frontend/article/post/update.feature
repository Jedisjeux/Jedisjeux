@ui @frontend @article @comment @update
Feature: Edit article comment
  In order to comment articles
  As a user
  I need to be able to edit my comments

  Background:
    Given there are following users:
      | email              | password | role      |
      | kevin@example.com  | password | ROLE_USER |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                        |
      | articles/actualites | Critique de Vroom Vroom      |
    And article "Critique de Vroom Vroom" has following comments:
      | author            |
      | kevin@example.com |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Update my comment
    Given I am on "/articles"
    And I follow "Critique de Vroom Vroom"
    And I follow "Modifier"
    When I press "Mettre à jour"
    Then I should see "a bien été mis à jour"