@ui @frontend @contactRequest @create
Feature: Contact request
    As a visitor
    I need to be able to contact jedisjeux

    Scenario: Contact as anonymous user
        Given I am on "/contact"
        And I fill in the following:
            | Email  | bobby.cyclette@example.com |
            | Nom    | Bobby                      |
            | Prénom | Cyclette                   |
            | Corps  | Here is my awesome message |
        When I press "Envoyer"
        Then I should see "a bien été envoyé"
