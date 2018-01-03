@ui @frontend @gamePlay @index
Feature: List game-plays
    In order to view game-plays
    As a user
    I need to be able to list game-plays

    Background:
        Given there are following users:
            | username | email             | password | role      |
            | kevin    | kevin@example.com | password | ROLE_USER |
        And there are products:
            | name                    |
            | Lewis & Clark           |
            | Les princes de Florence |
        And I am logged in as user "kevin@example.com" with password "password"

    @todo
    Scenario: List game-plays (game play must have a topic)
        Given there are game plays:
            | product                 | author            |
            | Lewis & Clark           | kevin@example.com |
            | Les princes de Florence | kevin@example.com |
        When I am on "/parties/"
        Then I should see "Lewis & Clark"
        And I should see "Les princes de Florence"

    @javascript
    Scenario: List my game-plays from top bar
        Given there are following users:
            | email           | password | role      |
            | 666@example.com | password | ROLE_USER |
        And there are game plays:
            | product                 | author            |
            | Les princes de Florence | kevin@example.com |
        And I am on homepage
        And I follow "kevin"
        And I wait "5" seconds until "$('.dropdown-menu').is('visible')"
        When I follow "Mes parties"
        Then I should see "Les princes de Florence"

    Scenario: List my game plays from account dashboard
        Given there are following users:
            | email           | password | role      |
            | 666@example.com | password | ROLE_USER |
        And there are game plays:
            | product                 | author            |
            | Les princes de Florence | kevin@example.com |
        And I am on "/mon-compte/accueil"
        When I follow "Mes parties de jeu"
        Then I should see "Les princes de Florence"

    @todo
    Scenario: Sorting game-plays (game play must have a topic)
        Given there are game plays:
            | product                 | author            |
            | Lewis & Clark           | kevin@example.com |
            | Les princes de Florence | kevin@example.com |
        And I am on "/parties/"
        When I follow "Date de création"
        Then I should see "Lewis & Clark"
