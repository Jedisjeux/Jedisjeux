@ui @frontend @topic @index
Feature: View list of topics
  In order to use forum
  As a visitor
  I need to be able to view all the topics

  Background:
    Given there are users:
      | email             |
      | kevin@example.com |
    And there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | forum  |
      | XYZ  | Réglons-ça      | forum  |
    And there are topics:
      | title                          | main_taxon            | author            |
      | Retour de Cannes jour par jour | forum/reglons-ca      | kevin@example.com |
      | Jeux avec handicap             | forum/moi-je-dis-jeux | kevin@example.com |

  Scenario: View list of topics
    When I am on "/topics/"
    Then I should see "Retour de Cannes jour par jour"
    And I should see "Jeux avec handicap"

  Scenario: View list of topics under a taxon
    Given I am on "/topics/"
    When I follow "Moi je dis jeux"
    Then I should see "Jeux avec handicap"
    But I should not see "Retour de Cannes jour par jour"