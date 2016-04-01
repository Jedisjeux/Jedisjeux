@backend @people @update
Feature: Edit people
  In order to manage people
  As an admin
  I need to be able to update people

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are people:
      | first_name | last_name |
      | Reiner     | Knizia    |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a person
    Given I am on "/admin/people/"
    And I follow "Modifier"
    And I fill in the following:
      | Prénom | Leo      |
      | Nom    | Colovini |
    When I press "Mettre à jour"
    Then I should see "a bien été mis à jour"