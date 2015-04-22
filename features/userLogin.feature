@userLogin
Feature: Sign in to jdj
    In order to view my orders list
    As a visitor
    I need to be able to log in to jdj

    Background:
        Given there are following users:
        | username | email                | password | enabled |
        | loic_425 | loic_425@hotmail.com | loic_425 | yes     |

    Scenario: Se connecter avec un utilisateur et un mot de passe depuis la page de login
        Given I am on "/"
          And I am on "/login"
         When I fill in the following:
            | Nom d'utilisateur | loic_425 |
            | Mot de passe      | loic_425 |
          And I press "Connexion"
         Then I should be on "/"

    Scenario: Se connecter avec des info incorrectes depuis la page de login
        Given I am on "/"
          And I am on "/login"
         When I fill in the following:
           | Nom d'utilisateur | loic_425 |
           | Mot de passe      | bar      |
          And I press "Connexion"
         Then I should be on "/login"
          And I should see "Droits invalides."

    Scenario: Se connecter sans aucune info depuis la page de login
        Given I am on "/"
          And I am on "/login"
         When I press "Connexion"
         Then I should be on "/login"
          And I should see "Droits invalides."

    Scenario: Trying to login as non existing user depuis la page de login
        Given I am on "/"
          And I am on "/login"
         When I fill in the following:
           | Nom d'utilisateur | toto     |
           | Mot de passe      | toto     |
          And I press "Connexion"
         Then I should be on "/login"
          And I should see "Droits invalides."

    Scenario: Se connecter avec un utilisateur et un mot de passe depuis la modal
        Given I am on "/"
        And I follow "topBarLoginDropdown"
        Then I should see "Je me connecte"
        When I fill in the following:
          | username | loic_425     |
          | password | loic_425     |
        And I press "submit-form-login"
        Then I should not see "Problème de connexion."
        And I should see "loic_425"
        And I should be on "/"

    Scenario: Se connecter avec un utilisateur et un mauvais mot de passe depuis la modal
        Given I am on "/"
        And I follow "topBarLoginDropdown"
        Then I should see "Je me connecte"
        When I fill in the following:
          | username | loic_425     |
          | password | sqqsd     |
        And I press "submit-form-login"
        Then I should see "Problème de connexion."
        And I should be on "/"