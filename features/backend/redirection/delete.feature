@ui @backend @redirection @delete
Feature: Remove redirections
  In order to manage redirections
  As an admin
  I need to be able to remove redirections

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are redirections:
      | source       | destination                 |
      | /puerto-rico | /jeu-de-societe/puerto-rico |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a redirection
    Given I am on "/admin/redirections/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a redirection with modal
    Given I am on "/admin/redirections/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"