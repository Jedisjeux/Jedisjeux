@bookEntries
Feature: Book entry creation
  In order to manage book entries
  As a user from office
  I need to be able to create a book entry

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And there are payment methods:
      | name   |
      | chèque |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Create a book entry
    Given I am on "/compta/ecriture/"
    And I follow "Créer une écriture comptable"
    When I fill in the following:
      | Libellé | remboursement blue |
      | Montant    | 20.34              |
      | Date    | 2015-03-21         |
    And I select "chèque" from "Moyen de paiement"
    And I select "débité" from "Etat"
    And I press "Créer"
    Then I should be on "/compta/ecriture/"
    And I should see "remboursement blue"