@bills @update
Feature: Bill creation
  In order to manage bills
  As a user from office
  I need to be able to update a bill

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
    And I am logged in as user "loic_425" with password "password"

  Scenario: Update a bill
    Given there are bills:
      | company | payment_method |
      | Ludibay | chèque         |
    And bill from customer "Ludibay" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And I am on "/compta/facture"
    And I follow "Modifier"
    When I fill in the following:
      | jdj_comptabundle_bill_billProducts_0_quantity | 2 |
    And I press "Modifier"
    And I am on "/compta/facture"
    Then I should see "35,99"
    But I should not see "25,99"