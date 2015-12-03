@bills @compta @search
Feature: Bill searching
  In order to manage book entries
  As a user from office
  I need to be able to search a bill

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And there are payment methods:
      | name   |
      | chèque |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And there are customers:
      | company   |
      | Philibert |
      | Ludibay   |
    And there are products:
      | name        | price |
      | Sex Toy     | 10.00 |
      | Playstation | 15.99 |
    And there are bills:
      | company   | payment_method |
      | Ludibay   | chèque         |
      | Philibert | chèque         |
    And bill from customer "Ludibay" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And bill from customer "Philibert" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Search a bill by customer
    Given I am on "/compta/facture"
    When I fill in the following:
     | criteria_query | Philibert
    And I press "Rechercher" on ".form-filter"
    Then I should see "Philibert"
    But I should not see "Ludibay"