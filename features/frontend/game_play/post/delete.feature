@ui @frontend @gamePlay @comment @delete
Feature: Remove game play comment
    In order to comment game plays
    As a user
    I need to be able to remove my comments

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

    Scenario: Remove my comment
        Given I am on "/parties/"
        And I follow "Lewis & Clark"
        When I press "Supprimer"
        Then I should see "a bien été supprimé"

    @javascript
    Scenario: Remove my comment with modal
        Given I am on "/parties/"
        And I follow "Lewis & Clark"
        When I press "Supprimer"
        And I wait until modal is visible
        And I follow "Supprimer"
        Then I should see "a bien été supprimé"
