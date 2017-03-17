@ui @backend @redirection @create
Feature: Creates redirections
  In order to manage redirections
  As an admin
  I need to be able to create redirections

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a redirection
    Given I am on "/admin/redirections/"
    And I follow "Créer"
    And I fill in the following:
      | Source      | /puerto-rico                |
      | Destination | /jeu-de-societe/puerto-rico |
    When I press "Créer"
    Then I should see "a bien été créé"

  Scenario: Cannot create empty redirection
    Given I am on "/admin/redirections/"
    And I follow "Créer"
    When I press "Créer"
    Then I should see "Cette valeur ne doit pas être vide."
