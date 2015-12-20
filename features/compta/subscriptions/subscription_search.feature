@subscriptions @compta @search
Feature: Subscription searching
  In order to manage subscriptions
  As a user from office
  I need to be able to search subscriptions

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
    And there are dealers:
      | name      |
      | Jedisjeux |
    And there are bills:
      | dealer    | company   | payment_method |
      | Jedisjeux | Ludibay   | chèque         |
      | Jedisjeux | Philibert | chèque         |
    And bill from customer "Ludibay" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And bill from customer "Philibert" has following products:
      | name        | quantity |
      | Sex Toy     | 1        |
      | Playstation | 1        |
    And I am logged in as user "loic_425" with password "password"
    And I am on "/compta/abonnement"

  Scenario: Search a subscription by customer
    Given I fill in the following:
      | criteria_query | Philibert
    When I press "Rechercher" on ".form-filter"
    Then I should see "Philibert"
    But I should not see "Ludibay"