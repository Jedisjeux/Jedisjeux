@ui @frontend @gamePlay @create
Feature: Game-play creation
  In order to manage game-plays
  As a user
  I need to be able to create new game-plays

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    And there are products:
      | name          |
      | Lewis & Clark |
    And I am logged in as user "kevin@example.com" with password "password"

  @javascript
  Scenario: Create new game-play
    Given I am on "/jeu-de-societe/lewis-clark"
    And I follow "Nouvelle partie"
    When I fill in the following:
      | Joué le | 2016-04-01 |
    And I press "Créer"
    Then I should see "a bien été créé"