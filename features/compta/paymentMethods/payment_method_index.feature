@paymentMethods
Feature: Payment list
  In order to manage payment methods
  As a user from office
  I need to be able to view list of payment methods

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: List payment methods
    Given there are payment methods:
      | name   |
      | chèque |
      | carte bancaire |
    And I am on "/compta/mode-paiement/"
    When I press "Supprimer"
    Then I should be on "/compta/mode-paiement/"
    And I should see "chèque"
    And I should see "carte bancaire"