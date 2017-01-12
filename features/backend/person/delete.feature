@ui @backend @person @delete
Feature: Remove people
  In order to manage people
  As an admin
  I need to be able to remove people

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are people:
      | first_name | last_name |
      | Reiner     | Knizia    |
    And I am logged in as user "admin@example.com" with password "password"

  @javascript
  Scenario: Remove a person
    Given I am on "/admin/people/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should see "a bien été supprimé"