@user
Feature: Sign in to the store
    In order to view my orders list
    As a visitor
    I need to be able to log in to the store

    Background:
        Given there are following users:
        | username | email                | password | enabled |
        | loic_425 | loic_425@hotmail.com | loic_425 | yes     |

    Scenario: Se connecter avec un utilisateur et un mot de passe
        Given I am on "/"
#          And I follow "/login"
          And I am on "/login"
         When I fill in the following:
            | Nom d'utilisateur | loic_425 |
            | Mot de passe      | loic_425 |
          And I press "Connexion"
         Then I should be on "/"

    Scenario: Se connecter avec des info incorrectes
        Given I am on "/"
          And I am on "/login"
         When I fill in the following:
           | Nom d'utilisateur | loic_425 |
           | Mot de passe      | bar      |
          And I press "Connexion"
         Then I should be on "/login"
          And I should see "Nom d'utilisateur ou mot de passe incorrect"

    Scenario: Se connecter sans aucune info
        Given I am on "/"
          And I am on "/login"
         When I press "Connexion"
         Then I should be on "/login"
          And I should see "Nom d'utilisateur ou mot de passe incorrect"

    Scenario: Trying to login as non existing user
        Given I am on "/"
          And I am on "/login"
         When I fill in the following:
           | Nom d'utilisateur | toto     |
           | Mot de passe      | toto     |
          And I press "Connexion"
         Then I should be on "/login"
          And I should see "Nom d'utilisateur ou mot de passe incorrect"
