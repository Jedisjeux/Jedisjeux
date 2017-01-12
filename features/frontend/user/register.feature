@ui @frontend @user @register
Feature: Register as a new user
  As a visitor
  I need to be able to register as a new user

  Scenario: Register as a new user
    Given I am on "/register"
    And I fill in the following:
      | Email                     | bobby.cyclette@example.com |
      | Nom d'utilisateur         | Bobby                      |
      | Mot de passe              | password                   |
      | Confirmation mot de passe | password                   |
    When I press "Valider"
    Then I should be on the homepage
    #And I should see "Merci pour votre inscription, vous allez recevoir un mail pour vérifier votre compte."