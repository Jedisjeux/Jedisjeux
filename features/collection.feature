@collection
Feature: Affichage de la fiche de jeu

  Background:
    Given there are following users:
      | user_id | username | email                | password | enabled |
      | 1       | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | 2       | superseb | blue@gmail.com       | superseb | yes     |
    And there are collections:
      | name            | description | username  |
      | Mes favoris     | dd          | loic_425  |
      | ma super liste  | 12          | loic_425  |
      | une top liste   | 12          | superseb  |


  Scenario: aller sur mes listes
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on "/collection/mes-listes"
    Then I should see "Mes listes"
    And I should see "ma super liste"
    And I should see "Mes favoris"


  Scenario: Je ne peux pas voir les listes des autres
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | superseb |
      | Mot de passe      | superseb |
    And I press "Connexion"
    And I am on "/collection/mes-listes"
    Then I should see "Mes listes"
    And I should see "une top liste"
    And I should not see "ma super liste"