@ui @backend @productReview @update
Feature: Edit product reviews
  In order to manage product reviews
  As an admin
  I need to be able to update product reviews

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

  Scenario: Update a product review
    Given I am on "/admin/product-reviews/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"