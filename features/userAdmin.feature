@userAdmin
Feature: Admin User account system (account administration)
  In order to administer users
  As an admin
  I need to be able to go to the user list

  Background:
    Given there are following users:
      | username | email | password | enabled | deletedAt |
      | user1 | user1@gmail.com | user1 | yes | NULL |
      | user2 | user2@gmail.com | user2 | yes | NULL |
    And user "user1" has following roles:
      | ROLE_ADMIN |
    And user "user2" has following roles:
      | ROLE_USER |

  Scenario: To connect to the user list with good infos
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | user1 |
      | Mot de passe | user1 |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"

  Scenario: To connect to the user list with bad role
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | user2 |
      | Mot de passe | user2 |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "403 forbidden"


  Scenario: To go to the user Creation
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | sbienvenu |
      | Mot de passe      | sbienvenu |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"
    And I should see "Ajouter un nouvel utilisateur"
    And I follow "Ajouter un nouvel utilisateur"
    Then I should be on "/espace-personnel/new"
    And I should see "Création d'un compte"

  Scenario: To go to the user edition page
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | sbienvenu |
      | Mot de passe      | sbienvenu |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"
    And I should see "édition"
    When I follow "édition"
    And I should see "Edition du compte de"


  Scenario: Edit a user
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | sbienvenu |
      | Mot de passe      | sbienvenu |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"
    And I should see "édition"
    When I follow "édition"
    And I should see "Edition du compte de"
    When I fill in the following:
      | jdj_userbundle_user[username]              | local el loco 2 |
      | jdj_userbundle_user[email]                 | localelloco2@gmail.com |
      | jdj_userbundle_user[plainPassword][first]  | loco2 |
      | jdj_userbundle_user[plainPassword][second] | loco2 |
      | Nom | LOCAL2  |
      | Prenom | El loco  |
      | Avatar | mon avatar  |
      | Presentation | test presentation  |
      | Date naissance | 1982-05-22  |
    And I press "Enregistrer"
    Then the response status code should be 200
    Then I should be on "/admin/user-list"
    And I should see "Le compte a bien été mis à jour."


  Scenario: Create a user
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | sbienvenu |
      | Mot de passe      | sbienvenu |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"
    And I should see "Ajouter un nouvel utilisateur"
    When I follow "Ajouter un nouvel utilisateur"
    And I should see "Création d'un compte"
    When I fill in the following:
      | jdj_userbundle_user[username]              | local el loco 3 |
      | jdj_userbundle_user[email]                 | localelloco3@gmail.com |
      | jdj_userbundle_user[plainPassword][first]  | loco3 |
      | jdj_userbundle_user[plainPassword][second] | loco3 |
      | Nom | LOCAL3  |
      | Prenom | El loco  |
      | Avatar | mon avatar  |
      | Presentation | test presentation  |
      | Date naissance | 1982-05-22  |
    And I press "Créer"
    Then the response status code should be 200
    Then I should be on "/admin/user-list"
    And I should see "Le compte a bien été créé."

  Scenario: Delete a user
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | sbienvenu |
      | Mot de passe      | sbienvenu |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"
    And I should see "Supprimer"
    When I press "Supprimer"
    Then the response status code should be 200
    And I should see "Le compte a bien été supprimée."