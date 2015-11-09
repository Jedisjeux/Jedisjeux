@customers @compta @update
Feature: Customer edition
  In order to manage customers
  As a user from office
  I need to be able to update a customer

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Update a customer
    Given there are customers:
      | company   |
      | Philibert |
    And I am on "/compta/client"
    And I follow "Modifier"
    When I fill in the following:
      | Société | Ludibay |
    And I press "Mettre à jour"
    Then I should be on "/compta/client/"
    And I should see "Ludibay"
    But I should not see "Philibert"