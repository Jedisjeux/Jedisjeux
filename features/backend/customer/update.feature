@ui @backend @customer @update
Feature: Edit customers
  In order to manage customers
  As an administrator
  I need to be able to update customers

  Background:
    Given there are users:
      | email             | role       | password |
      | kevin@example.com | ROLE_USER  | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are following users:
      | first_name | last_name | phone_number | cell_phone_number |
      | Pablo      | Kataire   | 0123456789   | 0123456789        |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a customer
    Given I am on "/admin/customers/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"