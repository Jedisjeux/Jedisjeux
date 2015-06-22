@jedizone
Feature: Workflow jedizone

  Background:
    Given there are games:
      | libelle     | age_min | joueur_min | joueur_max | status             |
      | Citadelles  | 10      | 2          | 8          | WRITING            |
      | Puerto Rico | 12      | 2          | 5          | NEED_A_TRANSLATION |
      | Monopoly    | 12      | 2          | 8          | NEED_A_REVIEW      |
      | Modern Art  | 12      | 3          | 5          | READY_TO_PUBLISH   |
      | El Grande   | 12      | 2          | 5          | PUBLISHED          |
    And there are following users:
      | username      | email                | password | enabled |
      | translator    | loic_425@hotmail.com | loic_425 | yes     |
      | reviewer      | blue@gmail.com       | blue     | yes     |
      | publisher     | toto@gmail.com       | toto     | yes     |
      | adminworkflow | tata@gmail.com       | tata     | yes     |
      | redactor      | titi@gmail.com       | titi     | yes     |
    And user "translator" has following roles:
      | ROLE_TRANSLATOR |
    And user "reviewer" has following roles:
      | ROLE_REVIEWER |
    And user "publisher" has following roles:
      | ROLE_PUBLISHER |
    And user "adminworkflow" has following roles:
      | ROLE_ADMIN_WORKFLOW |
    And user "redactor" has following roles:
      | ROLE_REDACTOR |
    And user "translator" has following roles:
      | ROLE_WORKFLOW |
    And user "reviewer" has following roles:
      | ROLE_WORKFLOW |
    And user "publisher" has following roles:
      | ROLE_WORKFLOW |
    And user "adminworkflow" has following roles:
      | ROLE_WORKFLOW |
    And user "redactor" has following roles:
      | ROLE_WORKFLOW |

  ###################
  ## Status WRITING
  ###################
  Scenario: Affichage de la fiche de jeu en status WRITING avec un role ROLE_WORKFLOW
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | reviewer |
      | Mot de passe      | blue     |
    And I press "Connexion"
    And I am on game "Citadelles"
    Then I should see "Ce jeu est encore au statut WRITING."
    And I should not see "Passer en Traduction"


  Scenario: Affichage de la fiche de jeu en status WRITING non connecté
    Given I am on game "Citadelles"
    Then I should not see "Ce jeu est encore au statut WRITING."
    And I should not see "Passer en Traduction"


  Scenario: Affichage de la fiche de jeu en status WRITING en tant que ROLE_REDACTOR et passage au status NEED_A_TRANSLATION
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | redactor |
      | Mot de passe      | titi     |
    And I press "Connexion"
    And I am on game "Citadelles"
    Then I should see "Ce jeu est encore au statut WRITING."
    And I should see "Passer en Traduction"
    And I follow "Passer en Traduction"
    And I wait "5" seconds
    Then I should see "Ce jeu est encore au statut NEED_A_TRANSLATION."
    And I should not see "Passer en relecture"

  ###################
  ## Status NEED_A_TRANSLATION
  ###################

  Scenario: Affichage de la fiche de jeu en status NEED_A_TRANSLATION avec un role ROLE_WORKFLOW
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | reviewer |
      | Mot de passe      | blue     |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    Then I should see "Ce jeu est encore au statut NEED_A_TRANSLATION."
    And I should not see "Passer en relecture"


  Scenario: Affichage de la fiche de jeu en status NEED_A_TRANSLATION non connecté
    Given I am on game "Puerto Rico"
    Then I should not see "Ce jeu est encore au statut WRITING."
    And I should not see "Passer en relecture"


  Scenario: Affichage de la fiche de jeu en status NEED_A_TRANSLATION en tant que ROLE_TRANSLATOR et passage au status NEED_A_REVIEW
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | translator |
      | Mot de passe      | loic_425   |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    Then I should see "Ce jeu est encore au statut NEED_A_TRANSLATION."
    And I should see "Passer en relecture"
    And I follow "Passer en relecture"
    And I wait "5" seconds
    Then I should see "Ce jeu est encore au statut NEED_A_REVIEW."
    And I should not see "Passer en publication"

  ##################
  # Status NEED_A_REVIEW
  ##################

  Scenario: Affichage de la fiche de jeu en status NEED_A_REVIEW avec un role ROLE_WORKFLOW
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | translator |
      | Mot de passe      | loic_425   |
    And I press "Connexion"
    And I am on game "Monopoly"
    Then I should see "Ce jeu est encore au statut NEED_A_REVIEW."
    And I should not see "Passer en publication"


  Scenario: Affichage de la fiche de jeu en status NEED_A_REVIEW non connecté
    Given I am on game "Monopoly"
    Then I should not see "Ce jeu est encore au statut NEED_A_REVIEW."
    And I should not see "Passer en publication"

  Scenario: Affichage de la fiche de jeu en status NEED_A_REVIEW en tant que ROLE_REVIEWER et passage au status READY_TO_PUBLISH
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | reviewer |
      | Mot de passe      | blue     |
    And I press "Connexion"
    And I am on game "Monopoly"
    Then I should see "Ce jeu est encore au statut NEED_A_REVIEW."
    And I should see "Passer en publication"
    And I follow "Passer en publication"
    And I wait "5" seconds
    Then I should see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should not see "Publier"

  ##################
  # Status READY_TO_PUBLISH
  ##################

  Scenario: Affichage de la fiche de jeu en status READY_TO_PUBLISH avec un role ROLE_WORKFLOW
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | translator |
      | Mot de passe      | loic_425   |
    And I press "Connexion"
    And I am on game "Modern Art"
    Then I should see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should not see "Publier"


  Scenario: Affichage de la fiche de jeu en status READY_TO_PUBLISH non connecté
    Given I am on game "Modern Art"
    Then I should not see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should not see "Publier"

  Scenario: Affichage de la fiche de jeu en status READY_TO_PUBLISH en tant que ROLE_PUBLISHER et passage au status PUBLISHED
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | publisher |
      | Mot de passe      | toto      |
    And I press "Connexion"
    And I am on game "Modern Art"
    Then I should see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should see "Publier"
    And I follow "Publier"
    And I wait "5" seconds
    Then I am on game "Modern Art"
    And I should not see "Publier"
    And I should not see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should not see "Ce jeu est encore au statut NEED_A_REVIEW."
    And I should not see "Ce jeu est encore au statut NEED_A_TRANSLATION."
    And I should not see "Ce jeu est encore au statut WRITING."
    Then I am on "/jedizone"
    And I should see "JediZone"
    And I should see "publisher a passé le jeu Modern Art en PUBLISHED"

  ######################
  # Decline status game
  ######################
  Scenario: Refus du passage au status PUBLISHED
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | publisher |
      | Mot de passe      | toto      |
    And I press "Connexion"
    And I am on game "Modern Art"
    Then I should see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should see "Refuser"
    And I follow "Refuser"
    And I wait "1" seconds
    Then I should see "Je refuse de passer le jeu Modern Art au statut PUBLISHED"
    And I fill in the following:
      | Commentaire | Local es realmente un pervertido  |
    When I follow "refuseStatus"
    Then I am on game "Modern Art"
    And I should see "Publier"
    And I should see "Ce jeu est encore au statut READY_TO_PUBLISH."
    And I should not see "Ce jeu est encore au statut NEED_A_REVIEW."
    And I should not see "Ce jeu est encore au statut NEED_A_TRANSLATION."
    And I should not see "Ce jeu est encore au statut WRITING."
    Then I am on "/jedizone"
    And I should see "JediZone"
    And I should see "publisher a refusé de passer le jeu Modern Art en READY_TO_PUBLISH"
    And I should see "Raison du refus : Local es realmente un pervertido"