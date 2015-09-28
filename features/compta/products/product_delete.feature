@products
Feature: Product removal
  In order to manage products
  As a user from office
  I need to be able to remove a product

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Remove a product
    Given there are products:
      | name    |
      | Sex Toy |
    When I am on "/compta/produit/"
    And I press "Supprimer"
    Then I should be on "/compta/produit/"
    And I should not see "Sex Toy"