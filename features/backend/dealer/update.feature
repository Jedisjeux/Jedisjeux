@ui @backend @dealer @update
Feature: Edit dealers
  In order to manage dealers
  As an admin
  I need to be able to update dealers

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are dealers:
      | name    |
      | Ludibay |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a dealer
    Given I am on "/admin/dealers/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"