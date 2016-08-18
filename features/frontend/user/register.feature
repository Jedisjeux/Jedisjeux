@ui @frontend @user @register
Feature: Register as a new user
  As a visitor
  I need to be able to register as a new user

  Scenario: Register as a new user
    Given I am on "/register"
    And I fill in the following:
      | Email                    | bobby.cyclette@example.com |
      | Mot de passe              | password                   |
      | Confirmation mot de passe | password                   |
      | Nom                       | Bobby                      |
      | Prénom                    | Cyclette                   |
      | Téléphone                 | 02 03 04 05 06             |
    When I press "Valider"
    Then I should see "a bien été créé"