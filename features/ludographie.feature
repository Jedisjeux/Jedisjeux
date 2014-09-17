@ludographie
Feature: Affichage de la ludographie

  Background:
    Given I am on "/ludographie/15/reiner-knizia"

  Scenario: Affichage de la page d'une ludographie
    Then I should see "Reiner Knizia"
     And I should see "Allemagne"
     And I should see "http://www.knizia.de"
     And I should see "Auteur de"
     And I should see "Amun-Re"
     And I should see "Le Seigneur des Anneaux"
     And I should not see "Tikal"

  Scenario: Acces à l'edition de la ludographie interdit
    When I am on "/ludographie/1/edit"
    Then I should be on "/login"

  Scenario: Affichage de la liste des ludographies
    When I am on "/ludographie"
    Then I should see "Les ludographies"
     And I should see "Reiner Knizia"
     And I should see "Wolfgang Kramer"

  Scenario: Affichage de la liste des avis des jeux de Knizia
    Then I should see "Coup de Maître !"
     And I should see "Tout ce que j'aime"