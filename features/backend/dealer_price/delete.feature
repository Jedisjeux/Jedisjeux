@ui @backend @dealerPrice @delete
Feature: Remove dealer prices
  In order to manage dealer prices
  As an admin
  I need to be able to remove dealer prices

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are dealers:
      | name      |
      | Philibert |
    Given I run import dealer prices command for "Philibert"
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a dealer price
    Given I am on "/admin/dealer-prices/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a dealer price with modal
    Given I am on "/admin/dealer-prices/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"