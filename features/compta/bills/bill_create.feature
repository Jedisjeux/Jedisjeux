@bills @compta @create
Feature: Bill creation
  In order to manage bills
  As a user from office
  I need to be able to create a bill

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
      | name        |
      | Sex Toy     |
      | Playstation |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Create a bill not paid yet
    Given I am on "/compta/facture/"
    And I follow "Créer une facture"
    And I select "Ludibay" from "Client"
    And I follow "label.add_product"
    And I select "Playstation" from "Produit"
    When I press "Créer"
    Then I should be on "/compta/facture/"
    And I should see "Ludibay"
    And I should see "en attente de paiement"