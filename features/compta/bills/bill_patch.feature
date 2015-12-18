@bills @compta @patch
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
    And there are dealers:
      | name      |
      | Jedisjeux |
    And there are customers:
      | company   |
      | Philibert |
      | Ludibay   |
    And there are products:
      | name        | price |
      | Sex Toy     | 10.00 |
      | Playstation | 15.99 |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Update payment date of a bill
    Given there are bills:
      | dealer    | company | payment_method |
      | Jedisjeux | Ludibay | chèque         |
    And bill from customer "Ludibay" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And I am on "/compta/facture"
    And I follow "En attente de paiement"
    And I wait until modal is visible
    And I fill in the following:
      | jdj_comptabundle_bill_payment[paidAt]        | 2015-12-02 |
    And I select "chèque" from "jdj_comptabundle_bill_payment[paymentMethod]"
    And I press "Sauvegarder"
    And I am on "/compta/ecriture"
    Then I should see "25.99"