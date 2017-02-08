@ui @backend @dealerPrice @update
Feature: Edit dealer prices
  In order to manage dealer prices
  As an admin
  I need to be able to update dealer prices

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are dealers:
      | name      |
      | Philibert |
    And I run import dealer prices command for "Philibert"
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a dealer
    Given I am on "/admin/dealer-prices/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"