@ui @frontend @account @update_profile
Feature: Update profile
    In order to manage my account
    As a user
    I need to be able to update my profile

    Background:
        Given there are users:
            | email             | password |
            | kevin@example.com | password |
        And I am logged in as user "kevin@example.com" with password "password"

    Scenario: Update my profile with existing email
        Given there are users:
            | email                      | password |
            | existing_email@example.com | password |
        And I am on "/mon-compte/accueil"
        And I follow "Modifier"
        And I fill in the following:
            | E-mail | existing_email@example.com |
        When I press "Enregistrer les modifications"
        Then I should see "Cet e-mail est déjà utilisé."
