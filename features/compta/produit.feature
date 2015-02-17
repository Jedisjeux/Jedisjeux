@produit
Feature: Les produits

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des produits
    When I am on "/compta/produit"
    And the response status code should be 200


  Scenario: Créer un produit
    When I am on "/compta/produit"
    And I follow "Créer un produit"
    Then I should be on "/compta/produit/new"
    And the response status code should be 200
    And I fill in the following:
      | Libelle | produit 1 |
    And I press "Créer"
    And I should be on "/compta/produit/"
    And I should see "produit 1"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un produit
    When I am on "/compta/produit"
    And I follow "Créer un produit"
    And I fill in the following:
      | Libelle | produit 1 |
    And I press "Créer"
    And I follow "Modifier"
    And the response status code should be 200
    And I fill in the following:
      | Libelle | produit 2 |
    And I press "Modifier"
    And I should be on "/compta/produit/"
    And I should see "produit 2"
    And I should not see "produit 1"

  Scenario: Supprimer un produit
    When I am on "/compta/produit"
    And I follow "Créer un produit"
    And I fill in the following:
      | Libelle | produit 1 |
    And I press "Créer"
    And I press "Supprimer"
    And I should be on "/compta/produit/"
    And the response status code should be 200
    And I should not see "produit 1"