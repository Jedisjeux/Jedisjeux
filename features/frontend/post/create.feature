@ui @frontend @post @create
Feature: Post creation
    In order to use forum
    As a user
    I need to be able to create new posts

    Background:
        Given there are following users:
            | email             | password | role      |
            | kevin@example.com | password | ROLE_USER |
        Given there are root taxons:
            | code  | name  |
            | forum | Forum |
        And there are taxons:
            | code | name            | parent |
            | 666  | Moi je dis jeux | forum  |
        And there are topics:
            | name               | main_taxon            | author            |
            | Jeux avec handicap | forum/moi-je-dis-jeux | kevin@example.com |
        And I am logged in as user "kevin@example.com" with password "password"

    Scenario: Body is required
        Given I am on "/topics/"
        And I follow "Lire le sujet"
        And I follow "Répondre au sujet"
        When I press "Créer"
        Then I should see "Cette valeur ne doit pas être vide"
