@frontend @persons @index
Feature: View persons
  In order to manage persons
  As a visitor
  I need to be able to view a person

  Background:
    Given there are persons:
      | first_name | last_name |
      | Reiner     | Knizia    |
      | Martin     | Wallace   |
      | Leo        | Colovini  |

  Scenario: View a person
    Given I am on "/ludographies/"
    When I follow "Reiner Knizia"
    Then I should see "Reiner Knizia"
    And I should see "Critiques"