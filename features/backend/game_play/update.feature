@ui @backend @gamePlay @update
Feature: Edit game plays
  In order to manage game plays
  As an admin
  I need to be able to update game plays

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

  Scenario: Update a game play
    Given I am on "/admin/game-plays/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"