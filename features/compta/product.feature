@comptaProduct
Feature: Les produits

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des produits
    When I am on "/compta/product"
    Then I should see "Liste des produits"


  Scenario: Créer un produit
    Given I am on "/compta/product"
    And I follow "Créer un produit"
    And I should be on "/compta/product/new/"
    When I fill in the following:
      | Libelle | produit 1 |
    And I press "Créer"
    Then I should be on "/compta/product/"
    And I should see "produit 1"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un produit
    Given I am on "/compta/produit/"
    And I follow "Créer un produit"
    And I fill in the following:
      | Libelle | produit 1 |
    And I press "Créer"
    And I follow "Modifier"
    When I fill in the following:
      | Libelle | produit 2 |
    And I press "Modifier"
    Then I should be on "/compta/product/"
    And I should see "produit 2"
    And I should not see "produit 1"

  Scenario: Supprimer un produit
    Given I am on "/compta/produit/"
    And I follow "Créer un produit"
    And I fill in the following:
      | Libelle | produit 1 |
    And I press "Créer"
    When I press "Supprimer"
    Then I should be on "/compta/product/"
    And I should not see "produit 1"