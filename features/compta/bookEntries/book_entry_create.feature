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
    Given I am on "/compta/book-entry/"
    And I follow "Créer une écriture comptable"
    And I should be on "/compta/ecriture/new/"
    When I fill in the following:
      | Libelle       | remboursement blue |
      | Montant       | 20.34              |
      | Date ecriture | 2015-03-21         |
    And I select "chèque" from "Mode reglement"
    And I select "débit" from "Sens"
    And I press "Créer"
    Then I should be on "/compta/book-entry/"
    And I should see "remboursement blue"