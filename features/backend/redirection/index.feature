@ui @backend @redirection @index
Feature: View list of redirections
  In order to manage redirections
  As an administrator
  I need to be able to view all the redirections

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are redirections:
      | source       | destination                 |
      | /patchwork   | /jeu-de-societe/patchwork   |
      | /puerto-rico | /jeu-de-societe/puerto-rico |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of redirections
    When I am on "/admin/redirections/"
    Then I should see "/patchwork"
    And I should see "/puerto-rico"
