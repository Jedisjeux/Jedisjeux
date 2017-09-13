@ui @backend @stringBlock @update
Feature: Edit string blocks
    In order to manage string blocks
    As an administrator
    I need to be able to update string blocks

    Background:
        Given there are users:
            | email             | role       | password |
            | admin@example.com | ROLE_ADMIN | password |
        And init doctrine phpcr repository
        And there are following string blocks:
            | name      |
            | block-one |
        And I am logged in as user "admin@example.com" with password "password"

    Scenario: Update a string block
        Given I am on "/admin/string-blocks/"
        And I follow "Modifier"
        When I press "Enregistrer les modifications"
        Then I should see "a bien été mis à jour"
