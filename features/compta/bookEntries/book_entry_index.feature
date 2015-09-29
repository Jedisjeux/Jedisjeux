@bookEntries
Feature: Book entry list
  In order to manage book entries
  As a user from office
  I need to be able to view list of book entries

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

  Scenario: List all book entries
    Given there are book entries:
      | label               | payment_method |
      | remboursement blue  | chèque         |
      | remboursement cyril | chèque         |
    When I am on "/compta/book-entry/"
    Then I should see "remboursement blue"
    And I should see "remboursement cyril"
