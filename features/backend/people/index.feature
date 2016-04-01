@backend @people @index
Feature: View list of people
  In order to manage people
  As an administrator
  I need to be able to view all the people

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are people:
      | first_name | last_name |
      | Reiner     | Knizia    |
      | Martin     | Wallace   |
      | Leo        | Colovini  |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of people
    When I am on "/admin/people/"
    Then I should see "Reiner Knizia"
    And I should see "Martin Wallace"
    And I should see "Leo Colovini"