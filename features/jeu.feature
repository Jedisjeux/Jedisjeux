@jeu
Feature: Affichage de la fiche de jeu

  Background:
    And I am on "/jeu/1/puerto-rico"

  Scenario: Affichage de la fiche de jeu
    Then I should see "Puerto Rico"
     And I should see "Un jeu de Andreas Seyfarth"
     And I should see "Illustré par Franz Vohwinkel"
     And I should see "édités par Alea, Ravensburger "
     And I should see "à partir de 12 ans"
     And I should see "de 2 à 5 joueurs"
     And I should see "Mécanismes : Combinaisons, Placement"
     And I should see "Thèmes : Commerce"
     And I should see "Matériel"
     And I should see "But du jeu"
     And I should see "Description du jeu"

  Scenario: Acces à l'edition de la fiche interdit
    When I am on "/jeu/1/edit"
    Then I should be on "/login"

  Scenario: Affichage de la liste de jeux
    When I am on "jeu/"
    Then I should see "Fiches de jeu"

  Scenario: Affichage d'un mécanisme et de la liste des jeux correspondants
    When I am on "jeu/mecanisme/1/encheres"
    Then I should see "Enchères"
     And I should see "Modern Art"
     And I should not see "Citadelles"

  Scenario: Affichage d'un thème et de la liste des jeux correspondants
    When I am on "jeu/theme/19/renaissance"
    Then I should see "Renaissance"
    And I should see "El Grande"
    And I should not see "Citadelles"


