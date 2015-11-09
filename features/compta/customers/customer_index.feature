@customers @compta @index
Feature: Customer list
  In order to manage customers
  As a user from office
  I need to be able to list all customers

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: List all customers
    Given there are customers:
      | company   |
      | Philibert |
      | Ludibay   |
    When I am on "/compta/client"
    And I should see "Philibert"
    And I should see "Ludibay"