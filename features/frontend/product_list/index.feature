@ui @frontend @productList @index
Feature: View list of product lists
    In order to manage product lists
    As a user
    I need to be able to view all my product lists

    Background:
        Given there are users:
            | email                  | password |
            | kevin@example.com      | password |
            | other_user@example.com | password |
        And there are product lists:
            | owner                  | name             |
            | kevin@example.com      | Liste de cadeaux |
            | other_user@example.com | Liste other user |
        And I am logged in as user "kevin@example.com" with password "password"

    Scenario: View my product lists
        When I am on "/mon-compte/accueil"
        And I follow "Mes listes de jeu"
        Then I should see "Liste de cadeaux"
        But I should not see "Liste other user"
