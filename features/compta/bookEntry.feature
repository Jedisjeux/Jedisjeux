@comptaBookEntry
Feature: The book entries

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
    And there are payment methods:
      | name |
      | chèque  |


  Scenario: Affichage de la liste des écritures
    When I am on "/compta/book-entry"
    And the response status code should be 200


  Scenario: Create a new book entry
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
    And I should see "ecriture 1"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Edit an existing book entry
    Given I am on "/compta/book-entry/"
    And I follow "Créer une écriture comptable"
    And I fill in the following:
      | Libelle       | remboursement blue |
      | Montant       | 20.34              |
      | Date ecriture | 2015-03-21         |
    And I select "chèque" from "Mode reglement"
    And I select "débit" from "Sens"
    And I press "Créer"
    And I follow "Modifier"
    When I fill in the following:
      | Libelle | remboursement cyril |
    And I press "Modifier"
    Then I should be on "/compta/book-entry/"
    And I should see "remboursement cyril"
    And I should not see "remboursement blue"

  Scenario: Delete a book entry
    Given I am on "/compta/book-entry/"
    And I follow "Créer une écriture comptable"
    When I fill in the following:
      | Libelle       | remboursement blue |
      | Montant       | 20.34              |
      | Date ecriture | 2015-03-21         |
    And I select "chèque" from "Mode reglement"
    And I select "débit" from "Sens"
    And I press "Créer"
    And I press "Supprimer"
    Then I should be on "/compta/book-entry/"
    And I should not see "remboursement blue"