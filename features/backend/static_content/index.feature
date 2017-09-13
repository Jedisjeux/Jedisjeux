@ui @backend @staticContent @index
Feature: View list of static contents
    In order to manage static contents
    As an administrator
    I need to be able to view all the static contents

    Background:
        Given there are users:
            | email             | role       | password |
            | admin@example.com | ROLE_ADMIN | password |
        And init doctrine phpcr repository
        And there are following static contents:
            | title                                    |
            | Nouveau logements quartier Mont-Gaillard |
        And I am logged in as user "admin@example.com" with password "password"

    @todo
    Scenario: View list of static contents
        When I am on "/admin/static-contents/"
        Then I should see "Nouveau logements quartier Mont-Gaillard"
