@ui @backend @gamePlay @delete
Feature: Remove game plays
  In order to manage game plays
  As an admin
  I need to be able to remove game plays

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
      | kevin@example.com | ROLE_USER  | password |
    And there are products:
      | name        |
      | Puerto Rico |
    And there are game plays:
      | product     | author            |
      | Puerto Rico | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a game play
    Given I am on "/admin/game-plays/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a game play with modal
    Given I am on "/admin/game-plays/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"