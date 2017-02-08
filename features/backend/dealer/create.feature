@ui @backend @dealer @create
Feature: Creates dealers
  In order to manage dealers
  As an admin
  I need to be able to create dealers

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a dealer
    Given I am on "/admin/dealers/"
    And I follow "Créer"
    And I fill in the following:
      | Code | ludibay |
      | Nom  | Ludibay |
    When I press "Créer"
    Then I should see "a bien été créé"