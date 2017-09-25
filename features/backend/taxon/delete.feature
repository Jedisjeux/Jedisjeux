@ui @backend @taxon @delete
Feature: Remove taxons
    In order to manage taxons
    As an admin
    I need to be able to remove taxons

    Background:
        Given there are users:
            | first_name | email             | role       | password |
            | Chuck      | admin@example.com | ROLE_ADMIN | password |
        And there are root taxons:
            | code   | name   |
            | themes | Thèmes |
        And there are taxons:
            | parent | name            |
            | themes | Science-fiction |
        And I am logged in as user "admin@example.com" with password "password"

    Scenario: Remove a root taxon
        Given I am on "/admin/taxons/"
        When I press "Supprimer"
        Then I should see "a bien été supprimée"

    @javascript
    Scenario: Remove a root taxon with modal
        Given I am on "/admin/taxons/"
        When I press "Supprimer"
        And I wait until modal is visible
        And I press confirm button
        Then I should see "a bien été supprimée"

    Scenario: Remove a child taxon
        Given I am on "/admin/taxons/"
        And I follow "Voir les sous-catégories"
        When I press "Supprimer"
        Then I should see "a bien été supprimée"

    @javascript
    Scenario: Remove a child taxon with modal
        Given I am on "/admin/taxons/"
        And I follow "Voir les sous-catégories"
        When I press "Supprimer"
        And I wait until modal is visible
        And I press confirm button
        Then I should see "a bien été supprimée"
