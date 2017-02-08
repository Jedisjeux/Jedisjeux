@ui @backend @dealer @delete
Feature: Remove dealers
  In order to manage dealers
  As an admin
  I need to be able to remove dealers

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are dealers:
      | name    |
      | Ludibay |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a dealer
    Given I am on "/admin/dealers/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a dealer with modal
    Given I am on "/admin/dealers/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"