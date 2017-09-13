@ui @backend @taxon @create
Feature: Creates taxons
    In order to manage taxons
    As an admin
    I need to be able to create taxons

    Background:
        Given there are users:
            | email             | role       | password |
            | admin@example.com | ROLE_ADMIN | password |
        And there are root taxons:
            | code   | name   |
            | themes | Thèmes |
        And there are taxons:
            | parent | name            |
            | themes | Science-fiction |
        And I am logged in as user "admin@example.com" with password "password"

    Scenario: Create a taxon without parent
        Given I am on "/admin/taxons/"
        And I follow "Créer"
        And I fill in the following:
            | Code | antique        |
            | Nom  | Antique        |
            | Slug | themes/antique |
        When I press "Créer"
        Then I should see "a bien été créée"

    Scenario: Create a taxon with a parent
        Given I am on "/admin/taxons/"
        And I follow "Voir les sous-catégories"
        And I follow "Créer"
        And I fill in the following:
            | Code | space-opera        |
            | Nom  | Space-Opéra        |
            | Slug | themes/space-opera |
        When I press "Créer"
        Then I should see "a bien été créée"

    @javascript @todo
    Scenario: Create a taxon with a parent
        Given I am on "/admin/taxons/"
        And I follow "Voir les sous-catégories"
        And I follow "Créer"
        And I fill in the following:
            | Code | space-opera |
            | Nom  | Space-Opéra |
        And I wait "2" seconds until "$('#sylius_taxon_translations_fr_FR_slug').val().length > 0"
        When I press "Créer"
        Then I should see "a bien été créée"
