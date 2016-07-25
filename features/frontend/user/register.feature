@ui @frontend @user @register
Feature: Register as a new user
  As a visitor
  I need to be able to register as a new user

  Scenario: Register as a new user
    Given I am on "/register"
    And I fill in the following:
      | E-mail                    | bobby.cyclette@example.com |
      | Mot de passe              | password                   |
      | Confirmation mot de passe | password                   |
      | Nom                       | Bobby                      |
      | Prénom                    | Cyclette                   |
      | Téléphone                 | 02 03 04 05 06             |
    And I select the "sylius_customer_registration_cgu" radio button
    When I press "Valider"
    Then I should see "a bien été créé"