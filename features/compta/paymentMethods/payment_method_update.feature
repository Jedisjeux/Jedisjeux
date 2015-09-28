@paymentMethods
Feature: Payment edition
  In order to manage payment methods
  As a user from office
  I need to be able to update a payment method

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Update a payment method
    Given there are payment methods:
      | name   |
      | chèque |
    And I am on "/compta/mode-paiement/"
    And I follow "Modifier"
    When I fill in the following:
      | Nom | carte bancaire |
    And I press "Mettre à jour"
    Then I should be on "/compta/mode-paiement/"
    And I should see "carte bancaire"
    But I should not see "chèque"