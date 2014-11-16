@userAdmin
Feature: Admin User account system (account administration)
  In order to administer users
  As an admin
  I need to be able to go to the user list

  Background:
    Given there are following users:
      | username  | email                            | password  | enabled |
      | sbienvenu | sebbath.bloody.sebbath@gmail.com | sbienvenu | yes     |
      | lfremont | lfremont@gmail.com | lfremont | yes |
    And user "sbienvenu" has following roles:
      | ROLE_ADMIN |
    And user "lfremont" has following roles:
      | ROLE_USER |

  Scenario: To connect to the user list with good infos
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | sbienvenu |
      | Mot de passe      | sbienvenu |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Liste des utilisateurs"

  Scenario: To connect to the user list with bad role
    Given I am on "/"
    And I am on "/admin/user-list"
    When I fill in the following:
      | Nom d'utilisateur | lfremont |
      | Mot de passe      | lfremont |
    And I press "Connexion"
    Then I should be on "/admin/user-list"
    And I should see "Access Denied"

