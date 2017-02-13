@ui @backend @productList @delete
Feature: Remove product lists
  In order to manage product lists
  As an admin
  I need to be able to remove product lists

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
      | kevin@example.com | ROLE_USER  | password |
    And there are product lists:
      | owner             | name             |
      | kevin@example.com | Liste de cadeaux |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a product list
    Given I am on "/admin/product-lists/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a product list with modal
    Given I am on "/admin/product-lists/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"