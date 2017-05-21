@ui @backend @redirection @update
Feature: Edit redirections
  In order to manage redirections
  As an admin
  I need to be able to update redirections

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are redirections:
      | source       | destination                 |
      | /puerto-rico | /jeu-de-societe/puerto-rico |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a redirection
    Given I am on "/admin/redirections/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"