@userAccount
Feature: create/edit my account and view my profile
  In order to login to the site
  As a visitor
  I need to be able to create/edit an account

  Background:
    Given there are following users:
      | username  | email                | password | enabled | presentation | dateNaissance |
      | toto      | toto@jdj.net         | toto     | yes     | blablabla    | 1983-04-27    |

  #############################
  ### Register
  #############################

  Scenario: Try to register with bad credentials
    Given I am on "/register/"
    When I fill in the following:
      | fos_user_registration_form[username]              | local el loco |
      | fos_user_registration_form[email]                 | localelloco@gmail.com |
      | fos_user_registration_form[plainPassword][first]  | loco |
      | fos_user_registration_form[plainPassword][second] | localoca |
      | fos_user_registration_form[presentation] | test presentation  |
      | fos_user_registration_form[dateNaissance] | 1982-05-22  |
    And I attach file "app/Resources/test/avatar.jpg" to "fos_user_registration_form[avatarFile]"
    And I press "Je m'inscris"
    And I should be on "/register/"
    And the response should contain "Les deux mots de passe ne correspondent pas."

  Scenario: Try to register with already used username
    Given I am on "/register/"
    When I fill in the following:
      | fos_user_registration_form[username]              | toto |
      | fos_user_registration_form[email]                 | localelloco@gmail.com |
      | fos_user_registration_form[plainPassword][first]  | loco |
      | fos_user_registration_form[plainPassword][second] | loco|
      | fos_user_registration_form[presentation] | test presentation  |
      | fos_user_registration_form[dateNaissance] | 1982-05-22  |
    And I attach file "app/Resources/test/avatar.jpg" to "fos_user_registration_form[avatarFile]"
    And I press "Je m'inscris"
    And I should be on "/register/"
    And the response should contain "Ce nom d'utilisateur est déjà utilisé."

  Scenario: Try to register with already used email
    Given I am on "/register/"
    When I fill in the following:
      | fos_user_registration_form[username]              | local el loco |
      | fos_user_registration_form[email]                 | toto@jdj.net |
      | fos_user_registration_form[plainPassword][first]  | loco |
      | fos_user_registration_form[plainPassword][second] | loco |
      | fos_user_registration_form[presentation] | test presentation  |
      | fos_user_registration_form[dateNaissance] | 1982-05-22  |
    And I attach file "app/Resources/test/avatar.jpg" to "fos_user_registration_form[avatarFile]"
    And I press "Je m'inscris"
    And I should be on "/register/"
    And the response should contain "Cette adresse mail est déjà utilisée sur JedisJeux."

  Scenario: To register an new account
    Given I am on "/register/"
    When I fill in the following:
      | fos_user_registration_form[username]              | local el loco |
      | fos_user_registration_form[email]                 | localelloco@gmail.com |
      | fos_user_registration_form[plainPassword][first]  | loco |
      | fos_user_registration_form[plainPassword][second] | loco |
      | fos_user_registration_form[presentation] | test presentation  |
      | fos_user_registration_form[dateNaissance] | 1982-05-22  |
    And I attach file "app/Resources/test/avatar.jpg" to "fos_user_registration_form[avatarFile]"
    And I press "Je m'inscris"
    And I should be on "/"
    And the response should contain "Votre compte a bien été créé."

  ###################
  ### Profile
  ###################

  Scenario: To connect to my profile
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | toto |
      | Mot de passe      | toto |
    And I press "Connexion"
    And I am on "/profile/"
    Then I should see "toto"

  ####################
  ### Edit my account
  ####################

  Scenario: Try to edit my profile with wrong credentials
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | toto |
      | Mot de passe      | toto |
    And I press "Connexion"
    And I am on "/profile/"
    And I follow "Editer mon compte"
    Then I should be on "/profile/edit"
    When I fill in the following:
      | fos_user_profile_form[username]           | toto                  |
      | fos_user_profile_form[email]              | toto@gmail.com        |
      | fos_user_profile_form[current_password]   | tototo                |
      | fos_user_profile_form[presentation]       | test presentation edit|
      | fos_user_profile_form[dateNaissance]      | 1982-05-22            |
    And I attach file "app/Resources/test/avatar.jpg" to "fos_user_profile_form[avatarFile]"
    And I press "Enregistrer"
    Then I should be on "/profile/edit"
    And I should see "Cette valeur doit être le mot de passe actuel de l'utilisateur."

  Scenario: To edit my profile
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | toto |
      | Mot de passe      | toto |
    And I press "Connexion"
    And I am on "/profile/"
    And I follow "Editer mon compte"
    Then I should be on "/profile/edit"
    When I fill in the following:
      | fos_user_profile_form[username]           | toto         |
      | fos_user_profile_form[email]              | toto@gmail.com |
      | fos_user_profile_form[current_password]   | toto                  |
      | fos_user_profile_form[presentation]       | test presentation edit|
      | fos_user_profile_form[dateNaissance]      | 1982-05-22            |
    And I attach file "app/Resources/test/avatar.jpg" to "fos_user_profile_form[avatarFile]"
    And I press "Enregistrer"
    Then I should be on "/profile/"
    And I should see "Le profil a été mis à jour "