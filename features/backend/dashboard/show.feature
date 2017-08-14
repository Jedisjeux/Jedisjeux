@ui @backend @dashboard
Feature: View backend dashboard
  In order to manage website
  As a staff user
  I need to be able to view backend dashboard

  Scenario: Admin user can access admin dashboard
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"
    When I am on "/admin/dashboard"
    Then the response status code should be 200

  Scenario: Staff user can access admin dashboard
    Given there are users:
      | email                | role          | password |
      | staff@example.com | ROLE_STAFF | password |
    And I am logged in as user "staff@example.com" with password "password"
    When I am on "/admin/dashboard"
    Then the response status code should be 200

  Scenario: Simple user cannot access admin dashboard
    Given there are users:
      | email            | role      | password |
      | user@example.com | ROLE_USER | password |
    And I am logged in as user "user@example.com" with password "password"
    When I am on "/admin/dashboard"
    Then the response status code should be 403