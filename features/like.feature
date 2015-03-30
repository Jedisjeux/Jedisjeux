@like
Feature: Gestion des likes

  Background:
    Given there are default status
    And there are default notes
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
    And there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | blue     | blue@gmail.com       | blue     | yes     |
      | toto     | toto@gmail.com       | toto     | yes     |
    And user "loic_425" has following roles:
      | ROLE_ADMIN |


  Scenario: Noter une critique comme étant utile
    Given game "Puerto Rico" has following user reviews:
      | username | note | libelle      | body                        |
      | loic_425 | 10   | un super jeu | c'est vraiment un super jeu |
    And I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | toto |
      | Mot de passe      | toto |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I follow "Critiques"
    And I press "J'aime"
    And I wait until loading has finished
    Then I should see "1" in the ".nbLikes" element
    And I should see "0" in the ".nbDislikes" element

  Scenario: Noter une critique comme étant inutile
    Given game "Puerto Rico" has following user reviews:
      | username | note | libelle         | body                           |
      | blue     | 10   | un jeu de merde | c'est vraiment un jeu de merde |
    And I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | toto |
      | Mot de passe      | toto |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I follow "Critiques"
    And I press "Je n'aime pas"
    And I wait until loading has finished
    Then I should see "1" in the ".nbDislikes" element
    And I should see "0" in the ".nbLikes" element

  Scenario: Passer de inutile à utile une critique
    Given game "Puerto Rico" has following user reviews:
      | username | note | libelle         | body                           |
      | blue     | 10   | un jeu de merde | c'est vraiment un jeu de merde |
    And I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | toto |
      | Mot de passe      | toto |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    And I follow "Critiques"
    And I press "Je n'aime pas"
    And I wait until loading has finished
    When I press "J'aime"
    And I wait until loading has finished
    Then I should see "0" in the ".nbDislikes" element
    And I should see "1" in the ".nbLikes" element