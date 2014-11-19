@gameList
Feature: View list of game

  Background:
    Given there are default status
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

  Scenario: View list of all games
    When I am on "jeu/"
    And I am on game list page
    Then I should see "Puerto Rico"
    And I should see "Modern Art"
    And I should see "El Grande"
    And I should see "Citadelles"

  Scenario: Filter on number of players
    Given I am on "jeu/"
    When I fill in the following:
      | form_joueurCount | 8 |
    And I press "Appliquer les critères"
    Then I should not see "Puerto Rico"
    And I should not see "Modern Art"
    And I should not see "El Grande"
    And I should see "Citadelles"
    When I fill in the following:
      | form_joueurCount | 4 |
    And I press "Appliquer les critères"
    Then I should see "Puerto Rico"
    And I should see "Modern Art"
    And I should see "El Grande"
    And I should see "Citadelles"

  Scenario: Filter on mechanisms
    Given I am on "jeu/"
    When I select "Combinaisons" from "form_mechanism"
    And I additionally select "Placement" from "form_mechanism"
    And I press "Appliquer les critères"
    Then I should not see "Modern Art"
    And I should not see "El Grande"
    And I should not see "Citadelles"
    But I should see "Puerto Rico"

  Scenario: Filter on themes
    Given I am on "jeu/"
    When I select "Renaissance" from "form_theme"
    And I press "Appliquer les critères"
    Then I should not see "Puerto Rico"
    And I should not see "Modern Art"
    And I should not see "Citadelles"
    But I should see "El Grande"

  Scenario: Filter on age
    Given I am on "jeu/"
    When I fill in the following:
      | form_ageMin | 10 |
    And I press "Appliquer les critères"
    Then I should not see "Puerto Rico"
    And I should not see "Modern Art"
    And I should not see "El Grande"
    But I should see "Citadelles"