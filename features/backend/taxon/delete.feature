@backend @taxon @delete
Feature: Remove taxons
  In order to manage taxons
  As an admin
  I need to be able to remove taxons

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
    And there are taxonomies:
      | code   | name   |
      | themes | Thèmes |
    And there are taxons:
      | parent | name            |
      | Thèmes | Science-fiction |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a taxon
    Given I am on "/admin/taxonomies/"
    And I follow "Détails"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should see "a bien été supprimé"