@ui @frontend @gamePlay @comment @update
Feature: Edit game play comment
    In order to comment game plays
    As a user
    I need to be able to edit my comments

    Background:
        Given there are following users:
            | email              | password | role      |
            | author@example.com | password | ROLE_USER |
            | kevin@example.com  | password | ROLE_USER |
        And there are products:
            | name          |
            | Lewis & Clark |
        And there are game plays:
            | product       | author             |
            | Lewis & Clark | author@example.com |
        And game play from "Lewis & Clark" product and "author@example.com" author has following comments:
            | author            |
            | kevin@example.com |
        And I am logged in as user "kevin@example.com" with password "password"

    Scenario: Update my comment
        Given I am on "/parties/"
        And I follow "Lewis & Clark"
        And I follow "Modifier"
        When I press "Enregistrer les modifications"
        Then I should see "a bien été mis à jour"
