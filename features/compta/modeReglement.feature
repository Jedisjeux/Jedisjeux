@modeReglement
Feature: Les modes de règlement

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des modes de règlement
    When I am on "/compta/mode-reglement"
    And the response status code should be 200


  Scenario: Créer un mode de règlement
    When I am on "/compta/mode-reglement"
    And I follow "Créer un mode de règlement"
    Then I should be on "/compta/mode-reglement/new"
    And the response status code should be 200
    And I fill in the following:
      | Libelle | chèque |
    And I press "Créer"
    And I should be on "/compta/mode-reglement/"
    And I should see "chèque"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un mode de règlement
    When I am on "/compta/mode-reglement"
    And I follow "Créer un mode de règlement"
    And I fill in the following:
      | Libelle | chèque |
    And I press "Créer"
    And I follow "Modifier"
    And the response status code should be 200
    And I fill in the following:
      | Libelle | carte bancaire |
    And I press "Modifier"
    And I should be on "/compta/mode-reglement/"
    And I should see "carte bancaire"
    And I should not see "chèque"

  Scenario: Supprimer un produit
    When I am on "/compta/mode-reglement"
    And I follow "Créer un mode de règlement"
    And I fill in the following:
      | Libelle | chèque |
    And I press "Créer"
    And I press "Supprimer"
    And I should be on "/compta/mode-reglement/"
    And the response status code should be 200
    And I should not see "chèque"