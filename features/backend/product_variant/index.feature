@ui @backend @productVariant @index
Feature: List variants of a product
  In order to manage variants of a product
  As an administrator
  I need to be able to list variants of a product

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code            | name         |
      | themes          | Thèmes       |
      | mechanisms      | Mécanismes   |
      | target-audience | Public cible |
    And there are products:
      | name                    |
      | Les princes de Florence |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: List product variants
    Given I am on "/admin/products/"
    When I click on "Gérer les variantes" dropdown
    And I follow "Liste des variantes"
    Then I should see "Les princes de Florence"