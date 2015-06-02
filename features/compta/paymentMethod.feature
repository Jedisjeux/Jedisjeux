@comptaPaymentMethod
Feature: Les modes de paiement

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |

  Scenario: Affichage de la liste des modes de paiement
    When I am on "/compta/payment-method"
    Then I should see "Liste des modes de paiement"

  Scenario: Créer un mode de paiement
    When I am on "/compta/payment-method"
    And I follow "Créer un mode de paiement"
    And I should be on "/compta/mode-reglement/new"
    When I fill in the following:
      | Libelle | chèque |
    And I press "Créer"
    Then I should be on "/compta/payment-method"
    And I should see "chèque"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un mode de paiement
    Given I am on "/compta/payment-method"
    And I follow "Créer un mode de paiement"
    And I fill in the following:
      | Libelle | chèque |
    And I press "Créer"
    And I follow "Modifier"
    When I fill in the following:
      | Libelle | carte bancaire |
    And I press "Modifier"
    Then I should be on "/compta/payment-method"
    And I should see "carte bancaire"
    And I should not see "chèque"

  Scenario: Supprimer un produit
    Given I am on "/compta/mode-reglement"
    And I follow "Créer un mode de paiement"
    And I fill in the following:
      | Libelle | chèque |
    And I press "Créer"
    When I press "Supprimer"
    Then I should be on "/compta/payment-method"
    And I should not see "chèque"