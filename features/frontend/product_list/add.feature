@ui @frontend @productList @add
Feature: Add a product to list
    In order to manage my lists
    As a visitor
    I need to be able to add a product to a list

    Background:
        Given there are following users:
            | email             | password | role      |
            | kevin@example.com | password | ROLE_USER |
        And there are root taxons:
            | code       | name       |
            | mechanisms | Mécanismes |
            | themes     | Thèmes     |
            | forums     | Forum      |
        And there are taxons:
            | code        | name       | parent     |
            | mechanism-1 | Majorité   | Mécanismes |
            | theme-1     | Historique | Thèmes     |
        And there are products:
            | name      |
            | Louis XIV |
        And I am logged in as user "kevin@example.com" with password "password"

    @javascript @todo
    Scenario: Add product to game library
        Given I am on "/jeux-de-societe/"
        And I follow "Louis XIV"
        And I wait "5" seconds
        And I press "Ajouter à"
        And I wait "5" seconds
        When I select the "Ludothèque" radio button
        And I wait "5" seconds
        Then I should see "Le jeu a bien été ajouté à votre liste"
