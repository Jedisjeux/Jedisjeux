@ui @backend @productReview @delete
Feature: Remove product reviews
  In order to manage product reviews
  As an admin
  I need to be able to remove product reviews

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
      | kevin@example.com | ROLE_USER  | password |
    And there are products:
      | name        |
      | Puerto Rico |
    And there are product reviews:
      | product     | author            | title     |
      | Puerto Rico | kevin@example.com | Super jeu |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a product review
    Given I am on "/admin/product-reviews/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a product review with modal
    Given I am on "/admin/product-reviews/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"