@ui @backend @product @update_status
Feature: Update product status
    In order to manage products
    As an admin
    I need to be able to update product status

    Background:
        Given there are users:
            | email                  | role            | password |
            | admin@example.com      | ROLE_ADMIN      | password |
            | redactor@example.com   | ROLE_REDACTOR   | password |
            | translator@example.com | ROLE_TRANSLATOR | password |
            | reviewer@example.com   | ROLE_REVIEWER   | password |
            | publisher@example.com  | ROLE_PUBLISHER  | password |
        And there are root taxons:
            | code            | name         |
            | themes          | Thèmes       |
            | mechanisms      | Mécanismes   |
            | target-audience | Public cible |
        And I am logged in as user "admin@example.com" with password "password"

    Scenario: Ask for translation as a redactor
        Given I am logged in as user "redactor@example.com" with password "password"
        And there are products:
            | name        | status |
            | Puerto Rico | new    |
        And I am on "/admin/products/"
        And I follow "Modifier"
        When I press "Demander une traduction"
        Then I should see "a bien été mis à jour"
        And there is a notification to "translator@example.com" for "Puerto Rico" product

    Scenario: Ask for review as a redactor
        Given I am logged in as user "redactor@example.com" with password "password"
        And there are products:
            | name        | status |
            | Puerto Rico | new    |
        And I am on "/admin/products/"
        And I follow "Modifier"
        When I press "Demander une relecture"
        Then I should see "a bien été mis à jour"
        And there is a notification to "reviewer@example.com" for "Puerto Rico" product

    Scenario: Ask for publication as a reviewer
        Given I am logged in as user "reviewer@example.com" with password "password"
        And there are products:
            | name        | status         |
            | Puerto Rico | pending_review |
        And I am on "/admin/products/"
        And I follow "Modifier"
        When I press "Demander la publication"
        Then I should see "a bien été mis à jour"
        And there is a notification to "publisher@example.com" for "Puerto Rico" product
