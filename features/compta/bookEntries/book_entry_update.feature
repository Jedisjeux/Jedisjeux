@bookEntries
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

  Scenario: Edit an existing book entry
    Given there are book entries:
      | label              | payment_method |
      | remboursement blue | chèque         |
    And I am on "/compta/ecriture/"
    And I follow "Modifier"
    And I wait "5" seconds
    When I fill in the following:
      | Libellé | remboursement cyril |
    And I press "Modifier"
    Then I should be on "/compta/ecriture/"
    And I should see "remboursement cyril"
    But I should not see "remboursement blue"