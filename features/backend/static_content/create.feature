@ui @backend @staticContent @create
Feature: Create new static content
    In order to manage static contents
    As an administrator
    I need to be able to create new static contents

    Background:
        Given there are users:
            | email             | role       | password |
            | admin@example.com | ROLE_ADMIN | password |
        And init doctrine phpcr repository
        And I am logged in as user "admin@example.com" with password "password"

    @javascript @todo
    Scenario: Create new static content
        Given I am on "/admin/static-contents/"
        And I follow "Créer"
        And I fill in the following:
            | Nom   | nouveau-logement-quartier-mont-gaillard  |
            | Titre | Nouveau logements quartier Mont-Gaillard |
        And I fill in wysiwyg field "sylius_static_content_body" with "<p>La résidence des Tourelles a réouvert ses portes après plusieurs mois de travaux. Construite en 1900 par Antoine Chegaray pour son fils Pierre et ses deux filles</p>"
        When I press "Créer"
        Then I should see "a bien été créé"
