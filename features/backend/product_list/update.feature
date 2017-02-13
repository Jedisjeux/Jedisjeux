@ui @backend @productList @update
Feature: Edit product lists
  In order to manage product lists
  As an admin
  I need to be able to update product lists

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
      | kevin@example.com | ROLE_USER  | password |
    And there are product lists:
      | owner             | name             |
      | kevin@example.com | Liste de cadeaux |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a product list
    Given I am on "/admin/product-lists/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"