@ui @frontend @person @index
Feature: View list of people
  In order to manage people
  As a visitor
  I need to be able to view all the people

  Background:
    Given there are people:
      | first_name | last_name |
      | Reiner     | Knizia    |
      | Martin     | Wallace   |
      | Leo        | Colovini  |

  Scenario: View list of people
    When I am on "/ludographies/"
    Then I should see "Reiner Knizia"
    And I should see "Martin Wallace"
    And I should see "Leo Colovini"