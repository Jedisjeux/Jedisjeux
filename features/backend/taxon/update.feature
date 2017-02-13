@ui @backend @taxon @update
Feature: Edit taxons
  In order to manage taxons
  As an admin
  I need to be able to update taxons

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code   | name   |
      | themes | Thèmes |
    And there are taxons:
      | parent | name            |
      | themes | Science-fiction |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a taxon
    Given I am on "/admin/taxons/"
    And I follow "Voir les sous-catégories"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mise à jour"