@paymentMethods @compta @delete
Feature: Payment removal
  In order to manage payment methods
  As a user from office
  I need to be able to remove a payment method

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Remove a payment method
    Given there are payment methods:
      | name   |
      | chèque |
    And I am on "/compta/mode-paiement/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should be on "/compta/mode-paiement/"
    And I should not see "chèque"