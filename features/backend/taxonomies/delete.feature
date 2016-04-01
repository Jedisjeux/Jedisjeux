@backend @taxonomies @delete
Feature: Remove taxonomies
  In order to manage taxonomies
  As an admin
  I need to be able to remove taxonomies

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are taxonomies:
      | code   | name   |
      | themes | Thèmes |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a taxonomy
    Given I am on "/admin/taxonomies/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should see "a bien été supprimé"