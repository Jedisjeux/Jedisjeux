@ui @backend @productReview @index
Feature: View list of product reviews
  In order to manage product reviews
  As an administrator
  I need to be able to view all the product reviews

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

  Scenario: View list of product reviews
    When I am on "/admin/product-reviews/"
    Then I should see "Super jeu"