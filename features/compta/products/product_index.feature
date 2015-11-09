@products @compta @index
Feature: Product list
  In order to manage products
  As a user from office
  I need to be able to view list of products

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: List products
    Given there are products:
      | name    |
      | Sex Toy |
      | Playstation |
    When I am on "/compta/produit/"
    Then I should see "Playstation"
    And I should see "Sex Toy"