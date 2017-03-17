@ui @frontend @security @register
Feature: Register as a new user
  As a visitor
  I need to be able to register as a new user

  Scenario: Register as a new user
    Given I am on "/register"
    And I fill in the following:
      | E-mail                    | kevin@example.com |
      | Nom d'utilisateur         | Kevin             |
      | Mot de passe              | password          |
      | Confirmer le mot de passe | password          |
    When I press "Valider"
    Then I should be on the homepage
    And I should see "Merci pour votre inscription, vous allez recevoir un mail pour vérifier votre compte."

  Scenario: Register with an existing email
    Given there are users:
      | email             |
      | kevin@example.com |
    And I am on "/register"
    And I fill in the following:
      | E-mail                    | kevin@example.com |
      | Nom d'utilisateur         | Kevin             |
      | Mot de passe              | password          |
      | Confirmer le mot de passe | password          |
    When I press "Valider"
    Then I should see "Cet e-mail est déjà utilisé."

  Scenario: Register with an existing username
    Given there are users:
      | username |
      | Kevin    |
    And I am on "/register"
    And I fill in the following:
      | E-mail                    | kevin@example.com |
      | Nom d'utilisateur         | Kevin             |
      | Mot de passe              | password          |
      | Confirmer le mot de passe | password          |
    When I press "Valider"
    Then I should see "Ce nom d'utilisateur est déjà utilisé."