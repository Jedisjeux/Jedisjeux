@ui @frontend @topic @index
Feature: View list of topics
  In order to use forum
  As a visitor
  I need to be able to view all the topics

  Background:
    Given there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | Forum  |
      | XYZ  | Réglons-ça      | Forum  |
    And there are topics:
      | title                          | main-taxon      |
      | Retour de Cannes jour par jour | Réglons-ça      |
      | Jeux avec handicap             | Moi je dis jeux |

  Scenario: View list of topics
    When I am on "/forum/topics/"
    Then I should see "Retour de Cannes jour par jour"
    And I should see "Jeux avec handicap"

  Scenario: View list of topics under a taxon
    Given I am on "/forum/topics/"
    When I follow "Moi je dis jeux"
    Then I should see "Jeux avec handicap"
    But I should not see "Retour de Cannes jour par jour"