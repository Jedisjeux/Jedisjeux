@userRegister
Feature: Register to the site
  In order to login to the site
  As a visitor
  I need to be able to create an account

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | sbienvenu | sebbath.bloody.sebbath@gmail.com | sbienvenu | yes     |

  Scenario: To register an new account
    Given I am on "/register/"
    When I fill in the following:
      | fos_user_registration_form[username]              | local el loco |
      | fos_user_registration_form[email]                 | localelloco@gmail.com |
      | fos_user_registration_form[plainPassword][first]  | loco |
      | fos_user_registration_form[plainPassword][second] | loco |
      | Nom | LOCAL  |
      | Prenom | El loco  |
      | Avatar | mon avatar  |
      | Presentation | test presentation  |
      | Date naissance | 1982-05-22  |
    And I press "Enregistrer"
    Then the response status code should be 200
    And I should be on "/profile/"
    And the response should contain "Votre compte a bien été créé. Veuillez maintenant vous connecter."

