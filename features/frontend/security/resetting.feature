@ui @frontend @security @resetting
Feature: Resetting password
    As a visitor
    I need to be able to resetting my password

    Scenario: Reset password
        Given there are users:
            | email                      | confirmation_token |
            | felicie.clyste@example.com | 666                |
        And I am on "/forgotten-password/666"
        And I fill in the following:
            | Mot de passe | password |
            | Vérification | password |
        When I press "Réinitialiser"
        Then I should see "Votre mot de passe a été réinitialisé avec succès"
