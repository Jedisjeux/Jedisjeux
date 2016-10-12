@ui @frontend @gamePlay @index
Feature: List game-plays
  In order to view game-plays
  As a user
  I need to be able to list game-plays

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    And there are products:
      | name                    |
      | Lewis & Clark           |
      | Les princes de Florence |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: List game-plays
    Given there are game plays:
      | product                 | author            |
      | Lewis & Clark           | kevin@example.com |
      | Les princes de Florence | kevin@example.com |
    When I am on "/parties/"
    Then I should see "Lewis & Clark"
    And I should see "Les princes de Florence"

  @javascript
  Scenario: List game-plays of logged user
    Given there are following users:
      | email           | password | role      |
      | 666@example.com | password | ROLE_USER |
    And there are game plays:
      | product                 | author            |
      | Lewis & Clark           | 666@example.com   |
      | Les princes de Florence | kevin@example.com |
    And I am on homepage
    And I follow "kevin@example.com"
    And I wait "2" seconds until "$('.dropdown-menu').is('visible')"
    When I follow "Mes parties"
    Then I should see "Les princes de Florence"
    But I should not see "Lewis & Clark"