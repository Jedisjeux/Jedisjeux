@ui @frontend @security @verify
Feature: Verify user email
    As an anonymous
    I need to be able to verify my email

    Background:
        Given there are users:
            | email_verification_token |
            | XYZ                      |

    Scenario: Verify user email
        When I am on "verify/XYZ"
        Then I should see "Votre adresse email a été vérifiée avec succès."
