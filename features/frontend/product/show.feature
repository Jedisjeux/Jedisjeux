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
      | forums     | Forum      |
    And there are taxons:
      | code        | name       | parent     |
      | mechanism-1 | Majorité   | Mécanismes |
      | theme-1     | Historique | Thèmes     |
    And there are products:
      | name      |
      | Louis XIV |
    And product "Louis XIV" has following taxons:
      | slug                |
      | mecanismes/majorite |
      | themes/historique   |

  Scenario: View product
    Given I am on "/jeux-de-societe/"
    When I follow "Louis XIV"
    Then I should see "Louis XIV"

  Scenario: Cannot access to a product with new status
    Given there are products:
      | name     | status |
      | New game | new    |
    When I am on "new-game" product page
    Then the response status code should be 404

  Scenario: Can access to a product with new status as a staff user
    Given there are products:
      | name     | status |
      | New game | new    |
    And there are users:
      | email             | role       | password |
      | staff@example.com | ROLE_STAFF | password |
    And I am logged in as user "staff@example.com" with password "password"
    When I am on "new-game" product page
    Then the response status code should be 200

  @javascript
  Scenario: View Articles tab
    Given I am on "/jeux-de-societe/"
    And I follow "Louis XIV"
    When I follow "Articles" on ".nav-tabs"
    And I wait "2" seconds
    Then I should see "Aucun article"

  @javascript
  Scenario: View Avis tab
    Given I am on "/jeux-de-societe/"
    And I follow "Louis XIV"
    When I follow "Avis" on ".nav-tabs"
    And I wait "2" seconds
    Then I should see "Aucun avis"