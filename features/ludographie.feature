@ludographie
Feature: Affichage de la ludographie

  Background:
    Given there are default status
    And there are personnes:
      | prenom | nom    | pays      | site_web             |
      | Reiner | Knizia | Allemagne | http://www.knizia.de |
    And there are games:
      | libelle                 | age_min | joueur_min | joueur_max |
      | Le Seigneur des Anneaux | 12      | 2          | 5          |
      | Modern Art              | 12      | 3          | 5          |
      | Puerto Rico             | 12      | 2          | 5          |
    And game "Puerto Rico" has following authors:
      | Andreas | Seyfarth |
    And game "Le Seigneur des Anneaux" has following authors:
      | Reiner | Knizia |
    And game "Modern Art" has following authors:
      | Reiner | Knizia |
    And game "Puerto Rico" has following illustrators:
      | Franz | Vohwinkel |
    And game "Le Seigneur des Anneaux" has following illustrators:
      | John | Howe |
    And game "Puerto Rico" has following editors:
      |  | Alea         |
      |  | Ravensburger |


  Scenario: Affichage de la page d'une ludographie
    Given I am on ludography of "Reiner Knizia"
    Then I should see "Reiner Knizia"
    And I should see "Allemagne"
    And I should see "http://www.knizia.de"
    And I should see "Auteur de 2 jeux"
    And I should see "Modern Art"
    And I should see "Le Seigneur des Anneaux"
    And I should not see "Puerto Rico"

  Scenario: Acces à l'edition de la ludographie interdit
    When I am on "/ludographie/1/edit"
    Then I should be on "/login"

  Scenario: Affichage de la liste des ludographies
    When I am on "/ludographie"
    Then I should see "Les ludographies"
    And I should see "Reiner Knizia"
    And I should see "Andreas Seyfarth"
    And I should see "Franz Vohwinkel"
    And I should see "Alea"

#  Scenario: Affichage de la liste des avis des jeux de Knizia
#    Then I should see "Coup de Maître !"
#    And I should see "Tout ce que j'aime"