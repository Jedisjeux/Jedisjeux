@ui @frontend @product @show
Feature: View a product
  In order to view information of a product
  As a visitor
  I need to be able to view a product sheet

  Background:
    Given there are root taxons:
      | code       | name       |
      | mechanisms | Mécanismes |
      | themes     | Thèmes     |
    And there are taxons:
      | code        | name       | parent     |
      | mechanism-1 | Majorité   | Mécanismes |
      | theme-1     | Historique | Thèmes     |
    And there are products:
      | name      |
      | Louis XIV |
    And product "Louis XIV" has following taxons:
      | permalink           |
      | mecanismes/majorite |
      | themes/historique   |

  Scenario: View product
    Given I am on "/jeux-de-societe/"
    When I follow "Louis XIV"
    Then I should see "Louis XIV"
    And I should see "Majorité"
    And I should see "Historique"