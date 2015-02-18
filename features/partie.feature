@partie
Feature: Les parties de jeux

  Background:
    Given there are default status
    And there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | blue     | blue@gmail.com       | blue     | yes     |
      | toto     | toto@gmail.com       | toto     | yes     |
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
      | Caylus      | 12      | 2          | 5          |
    And game "Puerto Rico" has following parties:
      | username | playedAt |
      | loic_425 | 22/05/2014  |
      | blue     | 18/04/2013  |

  Scenario: Affichage de la liste des parties d'un jeu
    When I am on game "Puerto Rico"
    Then I should see "2 Parties"

  Scenario: Création d'une partie
    When I am on game "Puerto Rico"
    And I follow "Créer une partie"
    Then I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on partie creation of "Puerto Rico"
    And I press "Créer"
    And I should see "La partie a bien été enregistrée"
    And I should see "Scores de la partie"
    And I should see "Ajouter un nouveau joueur"

  Scenario: Modifier une partie
    Given I am on game "Puerto Rico"
    And I follow "Créer une partie"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on partie creation of "Puerto Rico"
    When I press "Créer"
    And I follow "Modifier"
    And I press "Enregistrer"
    Then I should see "Vos modifications ont bien été enregistrées!"

  Scenario: Supprimer une partie
    Given I am on game "Puerto Rico"
    And I follow "Créer une partie"
    Then I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on partie creation of "Puerto Rico"
    And I press "Créer"
    When I press "Supprimer"
    Then I should see "La partie a été supprimée"


  Scenario: Ajouter un nouveau joueur
    Given I am on game "Caylus"
    And I follow "Créer une partie"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on partie creation of "Caylus"
    And I press "Créer"
    When I follow "Ajouter un nouveau joueur"
    Then I should be on joueur creation page
    And I fill in the following:
      | Nom | Cédric |
      | Score | 32 |
    And I press "Créer"
    And I should be on partie show page
    And I should see "Cédric"
    And I should see "32"
    And I follow "Ajouter un nouveau joueur"
    And I should be on joueur creation page
    And I fill in the following:
      | Nom | Loïc |
      | Score | 23 |
    And I press "Créer"
    And I should be on partie show page
    And I should see "Caylus"
    And I should see "Cédric"
    And I should see "32"
    And I should see "Loïc"
    And I should see "23"

  Scenario: Modifier un joueur
    Given I am on game "Caylus"
    And I follow "Créer une partie"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on partie creation of "Caylus"
    And I press "Créer"
    And I follow "Ajouter un nouveau joueur"
    And I should be on joueur creation page
    And I fill in the following:
      | Nom | Cédric |
      | Score | 32 |
    And I press "Créer"
    And I should be on partie show page
    And I should see "Cédric"
    And I should see "32"
    When I follow "édition"
    Then I should be on joueur edition page
    And I fill in the following:
      | Nom | Loïc |
      | Score | 23 |
    And I press "Enregistrer"
    And I should be on partie show page
    And I should see "Caylus"
    And I should see "Loïc"
    And I should see "23"
    And I should not see "Cédric"
    And I should not see "32"

  Scenario: Supprimer un joueur
    Given I am on game "Caylus"
    And I follow "Créer une partie"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on partie creation of "Caylus"
    And I press "Créer"
    And I follow "Ajouter un nouveau joueur"
    And I should be on joueur creation page
    And I fill in the following:
      | Nom | Cédric |
      | Score | 32 |
    And I press "Créer"
    And I should be on partie show page
    And I should see "Cédric"
    And I should see "32"
    When I press "Supprimer le joueur"
    Then I should be on partie show page
    And I should see "Le Joueur a été supprimé"
    And I should not see "Cédric"
    And I should not see "32"
