@ui @frontend @topic @index
Feature: View list of topics
  In order to use forum
  As a visitor
  I need to be able to view all the topics

  Background:
  TODO set forum categories
    Given there are root taxons:
      | code  |
      | forum |
    And there are topics:
      | title                          |
      | Retour de Cannes jour par jour |
      | Jeux avec handicap             |

  Scenario: View list of topics
    When I am on "/forum/topics/"
    Then I should see "Retour de Cannes jour par jour"
    And I should see "Jeux avec handicap"