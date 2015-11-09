@customers @compta @delete
Feature: Customer removal
  In order to manage customers
  As a user from office
  I need to be able to delete a customer

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Delete a customer
    Given there are customers:
      | company   |
      | Philibert |
    And I am on "/compta/client"
    When I press "Supprimer"
    Then I should be on "/compta/client/"
    And I should not see "Philibert"