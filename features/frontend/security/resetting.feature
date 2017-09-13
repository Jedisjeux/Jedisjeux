@ui @frontend @security @resetting
Feature: Resetting password
    As a visitor
    I need to be able to resetting my password

    Scenario: Request token
        Given there are users:
            | email                      | password |
            | bobby.cyclette@example.com | password |
        And I am on "/forgotten-password"
        And I fill in the following:
            | E-mail | bobby.cyclette@example.com |
        When I press "Valider"
        Then I should see "vous recevrez un message avec les instructions pour réinitialiser votre mot de passe"

    Scenario: Reset password
        Given there are users:
            | email                      | confirmation_token |
            | felicie.clyste@example.com | 666                |
        And I am on "/forgotten-password/666"
        And I fill in the following:
            | Mot de passe | password |
            | Vérification | password |
        When I press "Valider"
        Then I should see "Votre mot de passe a été réinitialisé avec succès"
