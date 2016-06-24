@ui @frontend @product @index
Feature: View list of products
  In order to navigate on products list
  As a visitor
  I need to be able to view all the products

  Background:
    Given there are root taxons:
      | code       | name       |
      | mechanisms | Mécanismes |
    And there are taxons:
      | code        | name     | parent     |
      | mechanism-1 | Enchères | Mécanismes |
      | mechanism-2 | Majorité | Mécanismes |
    And there are products:
      | name      | main-taxon |
      | Palazzo   | Enchères   |
      | Louis XIV | Majorité   |

  Scenario: View list of products
    When I am on "/jeux-de-societe/"
    Then I should see "Palazzo"
    And I should see "Louis XIV"

  Scenario: View list of products under a taxon
    Given I am on "/jeux-de-societe/"
    When I follow "Enchères"
    Then I should see "Palazzo"
    But I should not see "Louis XIV"