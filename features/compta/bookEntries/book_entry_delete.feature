@bookEntries @compta @delete
Feature: Book entry edition
  In order to manage book entries
  As a user from office
  I need to be able to update a book entry

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

  Scenario: Delete a book entry
    Given there are book entries:
      | label              | payment_method |
      | remboursement blue | chèque         |
    And I am on "/compta/ecriture/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should be on "/compta/ecriture/"
    And I should not see "remboursement blue"