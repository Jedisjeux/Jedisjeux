@bookEntries @compta @search
Feature: Book entry searching
  In order to manage book entries
  As a user from office
  I need to be able to search book entries

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And there are payment methods:
      | name   |
      | chèque |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And there are book entries:
      | label               | payment_method | price |
      | remboursement blue  | chèque         | 10.23 |
      | remboursement cyril | chèque         | 20.68 |
    And I am logged in as user "loic_425" with password "password"
    And I am on "/compta/ecriture/"

  Scenario: Search book entries by label
    Given I fill in the following:
      | criteria_query | blue
    When I press "Rechercher" on ".form-filter"
    Then I should see "remboursement blue"
    But I should not see "remboursement cyril"

  Scenario: Search book entries by label
    Given I fill in the following:
      | criteria_query | 10.2
    When I press "Rechercher" on ".form-filter"
    Then I should see "remboursement blue"
    But I should not see "remboursement cyril"
