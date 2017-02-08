@ui @backend @dealer @index
Feature: View list of dealers
  In order to manage dealers
  As an administrator
  I need to be able to view all the dealers

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are dealers:
      | name      |
      | Ludibay   |
      | Philibert |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of dealers
    When I am on "/admin/dealers/"
    Then I should see "Ludibay"
    And I should see "Philibert"
