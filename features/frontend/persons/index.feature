@frontend @persons @index
Feature: View list of persons
  In order to manage persons
  As a visitor
  I need to be able to view all the persons

  Background:
    Given there are persons:
      | first_name | last_name |
      | Reiner     | Knizia    |
      | Martin     | Wallace   |
      | Leo        | Colovini  |

  Scenario: View list of persons
    When I am on "/ludographies/"
    Then I should see "Reiner Knizia"
    And I should see "Martin Wallace"
    And I should see "Leo Colovini"