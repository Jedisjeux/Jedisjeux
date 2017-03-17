@ui @backend @dealerPrice @index
Feature: View list of dealer prices
  In order to manage dealer prices
  As an administrator
  I need to be able to view all the dealer prices

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are dealers:
      | name      |
      | Philibert |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of dealer prices
    Given I run import dealer prices command for "Philibert"
    When I am on "/admin/dealer-prices/"
    Then I should see "Philibert"
