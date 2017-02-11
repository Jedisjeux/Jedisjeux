@ui @backend @customer @index
Feature: View list of customers
  In order to manage customers
  As an admin
  I need to be able to view all the customers

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of customers
    When I am on "/admin/customers/"
    Then I should see "admin@example.com"