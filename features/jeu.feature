@jeu
Feature: Affichage de la fiche de jeu

  Background:
    Given there are default status
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
      | Modern Art  | 12      | 3          | 5          |
      | El Grande   | 12      | 2          | 5          |
      | Citadelles  | 10      | 2          | 8          |
    And there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | blue     | blue@gmail.com       | blue     | yes     |
      | toto     | toto@gmail.com       | toto     | yes     |
    And user "loic_425" has following roles:
      | ROLE_ADMIN |
    And game "Puerto Rico" has following mechanisms:
      | Combinaisons |
      | Placement    |
    And game "Modern Art" has following mechanisms:
      | Enchères |
    And game "Puerto Rico" has following themes:
      | Commerce |
    And game "El Grande" has following themes:
      | Renaissance |
    And game "Puerto Rico" has following authors:
      | Andreas | Seyfarth |
    And game "Puerto Rico" has following illustrators:
      | Franz | Vohwinkel |
    And game "Puerto Rico" has following editors:
      |  | Alea         |
      |  | Ravensburger |


  Scenario: Affichage de la fiche de jeu
    When I am on game "Puerto Rico"
    Then I should see "Puerto Rico"
    And I should see "Un jeu de Andreas Seyfarth"
    And I should see "Illustré par Franz Vohwinkel"
    And I should see "édités par Alea, Ravensburger "
    And I should see "à partir de 12 ans"
    And I should see "de 2 à 5 joueurs"
    And I should see "Mécanismes : Combinaisons, Placement"
    And I should see "Thèmes : Commerce"
    #And I should see "Matériel"
    #And I should see "But du jeu"
    #And I should see "Description du jeu"

  Scenario: Acces à l'edition de la fiche interdit
    When I am on "/jeu/1/edit"
    Then I should be on "/login"

  Scenario: Lien vers un mécanisme et de la liste des jeux correspondants
    When I am on game "Modern Art"
    And I follow "Enchères"
    Then I should be on mechanism "Enchères"
    And I should see "Enchères"
    And I should see "Modern Art"
    But I should not see "Citadelles"

  Scenario: Lien vers un thème et de la liste des jeux correspondants
    When I am on game "El Grande"
    And I follow "Renaissance"
    Then I should be on theme "Renaissance"
    And I should see "Renaissance"
    And I should see "El Grande"
    But I should not see "Citadelles"
    And I should not see "Puerto Rico"

  Scenario: Lien vers un auteur et de la liste des jeux correspondants
    When I am on game "Puerto Rico"
    And I follow "Andreas Seyfarth"
    Then I should be on ludography of "Andreas Seyfarth"
    And I should see "Puerto Rico"
    But I should not see "Citadelles"
    And I should not see "El Grande"

  Scenario: Création d'une fiche de jeu
    Given I am on "/jeu/new"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on "/jeu/new"
    When I fill in the following:
      | Libelle | Jojo Lapin |
    And I press "Créer"
    Then I should be on game "Jojo Lapin"
    And I should see "Jojo Lapin"

  Scenario: Modification d'une fiche de jeu
    Given I am on edition page of the game "Puerto Rico"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on edition page of the game "Puerto Rico"
    When I fill in the following:
      | Libelle | Jojo Lapin |
    And I press "Modifier"
    Then I should see "Jojo Lapin"

