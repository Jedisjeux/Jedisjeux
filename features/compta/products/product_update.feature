@products
Feature: Product edition
  In order to manage products
  As a user from office
  I need to be able to update a product

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Update a product
    Given there are products:
      | name    |
      | Sex Toy |
    And I am on "/compta/produit/"
    And I follow "Modifier"
    When I fill in the following:
      | Nom | Playstation |
    And I press "Modifier"
    Then I should be on "/compta/produit/"
    And I should see "Playstation"
    But I should not see "Sex Toy"