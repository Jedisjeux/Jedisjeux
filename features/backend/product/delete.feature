@ui @backend @product @delete
Feature: Remove products
  In order to manage products
  As an admin
  I need to be able to remove products

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code            | name         |
      | themes          | Thèmes       |
      | mechanisms      | Mécanismes   |
      | target-audience | Public cible |
    And there are taxons:
      | parent | name            |
      | themes | Science-fiction |
      | themes | Fantastique     |
    And there are products:
      | name                    |
      | Puerto Rico             |
      | Les princes de Florence |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a product
    Given I am on "/admin/products/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a product with modal
    Given I am on "/admin/products/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"