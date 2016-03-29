@backend @users @index
Feature: View list of users
  In order to manage users
  As an admin
  I need to be able to view all the users

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of users
    When I am on "/admin/users/"
    Then I should see "admin@example.com"