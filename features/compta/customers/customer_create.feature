@customers
Feature: Customer creation
  In order to manage customers
  As a user from office
  I need to be able to create a customer

  Background:
    Given there are following users:
      | username | password |
      | loic_425 | password |
    And user "loic_425" has following roles:
      | ROLE_OFFICE |
    And I am logged in as user "loic_425" with password "password"

  Scenario: Create a customer
    Given I am on "/compta/client/"
    And I follow "Créer un client"
    And I should be on "/compta/client/new"
    When I fill in the following:
      | Société              | Philibert                   |
      | Email                | jedisjeux@jedisjeux.net     |
      | Rue                  | 2 allée de la châtaigneraie |
      | Complément d'adresse | bât C002                    |
      | Code postal          | 35740                       |
      | Ville                | Pacé                        |
    And I press "Créer"
    Then I should be on "/compta/client/"
    And I should see "Philibert"
    And I should see "Modifier"
    And I should see "Supprimer"