@userReview
Feature: Affichage des avis

  Background:
    Given there are default status
    And there are default notes
    And there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | blue     | blue@gmail.com       | blue     | yes     |
      | toto     | toto@gmail.com       | toto     | yes     |
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
    And game "Puerto Rico" has following user reviews:
      | username | note | libelle         | body                           |
      | loic_425 | 10   | un super jeu    | c'est vraiment un super jeu    |
      | blue     | 1    | un jeu de merde | c'est vraiment un jeu de merde |


  Scenario: Affichage de la liste des avis d'une fiche de jeu
    When I am on game "Puerto Rico"
    Then I should see "un super jeu"
    And I should see "un jeu de merde"