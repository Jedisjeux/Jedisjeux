@ui @backend @gamePlay @index
Feature: View list of game plays
  In order to manage game plays
  As an administrator
  I need to be able to view all the game plays

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

  Scenario: View list of game plays
    When I am on "/admin/game-plays/"
    Then I should see "Puerto Rico"