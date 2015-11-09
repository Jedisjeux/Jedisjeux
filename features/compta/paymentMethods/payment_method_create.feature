@paymentMethods @compta @create
Feature: Payment creation
  In order to manage payment methods
  As a user from office
  I need to be able to create a payment method

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Create a payment method
    When I am on "/compta/mode-paiement/"
    And I follow "Créer un mode de paiement"
    And I should be on "/compta/mode-paiement/new"
    When I fill in the following:
      | Nom | chèque |
    And I press "Créer"
    Then I should be on "/compta/mode-paiement/"
    And I should see "chèque"
    And I should see "Modifier"
    And I should see "Supprimer"