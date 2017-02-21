@ui @backend @product @update
Feature: Edit products
  In order to manage products
  As an admin
  I need to be able to update products

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code            | name         |
      | themes          | Thèmes       |
      | mechanisms      | Mécanismes   |
      | target-audience | Public cible |
    And there are products:
      | name        |
      | Puerto Rico |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a product
    Given I am on "/admin/products/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"