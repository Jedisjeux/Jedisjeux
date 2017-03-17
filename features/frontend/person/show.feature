@ui @frontend @person @show
Feature: View people
  In order to manage people
  As a visitor
  I need to be able to view a person

  Background:
    Given there are root taxons:
      | code  | name  |
      | zones | Zones |
    And there are people:
      | first_name | last_name |
      | Reiner     | Knizia    |
      | Martin     | Wallace   |
      | Leo        | Colovini  |

  Scenario: View a person
    Given I am on "/ludographies/"
    When I follow "Reiner Knizia"
    Then I should see "Reiner Knizia"
    And I should see "Avis"