@products @compta @create
Feature: Product creation
  In order to manage products
  As a user from office
  I need to be able to create a product

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Create a product
    Given I am on "/compta/produit/"
    And I follow "Créer un produit"
    And I should be on "/compta/produit/new"
    When I fill in the following:
      | Nom | produit 1 |
      | Prix | 12.23 |
      | Durée de l'abonnement | 12 |
    And I press "Créer"
    Then I should be on "/compta/produit/"
    And I should see "produit 1"
    And I should see "Modifier"
    And I should see "Supprimer"