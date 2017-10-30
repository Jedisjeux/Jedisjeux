@ui @frontend @gamePlay @update
Feature: Edit game-plays
    In order to manage my game-plays
    As a user
    I need to be able to edit my game-plays

    Background:
        Given there are following users:
            | email             | password | role      |
            | kevin@example.com | password | ROLE_USER |
        And there are products:
            | name          |
            | Lewis & Clark |
        And there are game plays:
            | product       | author            |
            | Lewis & Clark | kevin@example.com |
        And I am logged in as user "kevin@example.com" with password "password"

    Scenario: Update my game play
        Given I am on "/mon-compte/parties/"
        And I follow "Lewis & Clark"
        And I follow "Modifier la partie"
        When I press "Enregistrer les modifications"
        Then I should see "a bien été mise à jour"
