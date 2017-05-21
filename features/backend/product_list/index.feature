@ui @backend @productList @index
Feature: View list of product lists
  In order to manage product lists
  As an administrator
  I need to be able to view all the product lists

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
      | kevin@example.com | ROLE_USER  | password |
    And there are product lists:
      | owner             | name             |
      | kevin@example.com | Liste de cadeaux |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of product lists
    When I am on "/admin/product-lists/"
    Then I should see "Liste de cadeaux"