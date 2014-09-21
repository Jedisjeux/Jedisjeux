@jeu
Feature: Affichage de la fiche de jeu

  Background:
    Given there are default status:
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
      | Modern Art  | 12      | 3          | 5          |
      | El Grande   | 12      | 2          | 5          |
      | Citadelles  | 10      | 2          | 8          |
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

  Scenario: Affichage de la liste de jeux
    When I am on "jeu/"
    Then I should see "Fiches de jeu"
    And I should see "Puerto Rico"
    And I should see "Modern Art"
    And I should see "El Grande"
    And I should see "Citadelles"


  Scenario: Affichage d'un mécanisme et de la liste des jeux correspondants
    When I am on game "Modern Art"
    And I follow "Enchères"
    Then I should be on mechanism "Enchères"
    And I should see "Enchères"
    And I should see "Modern Art"
    And I should not see "Citadelles"

  Scenario: Affichage d'un thème et de la liste des jeux correspondants
    When I am on game "El Grande"
    And I follow "Renaissance"
    Then I should be on theme "Renaissance"
    And I should see "Renaissance"
    And I should see "El Grande"
    And I should not see "Citadelles"
    And I should not see "Puerto Rico"
