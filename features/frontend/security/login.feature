@ui @frontend @security @login
Feature: Login as a user
    As a visitor
    I need to be able to login as a user

    Background:
        Given there are users:
            | email                      | password |
            | bobby.cyclette@example.com | password |

    Scenario: Login as a user
        Given I am on "/login"
        And I fill in the following:
            | Nom d'utilisateur ou email | bobby.cyclette@example.com |
            | Mot de passe               | password                   |
        When I press "Connexion"
        Then I should be on "/"
