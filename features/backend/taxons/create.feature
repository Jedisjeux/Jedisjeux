@backend @taxons @create
Feature: Creates taxons
  In order to manage taxons
  As an admin
  I need to be able to create taxons

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are taxonomies:
      | code   | name   |
      | themes | Thèmes |
    And there are taxons:
      | parent | name            |
      | Thèmes | Science-fiction |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a taxon without parent
    Given I am on "/admin/taxonomies/"
    And I follow "Détails"
    And I follow "Nouveau taxon"
    And I fill in the following:
      | Code | antique |
      | Nom  | Antique |
    When I press "Créer"
    Then I should see "a bien été créé"

  Scenario: Create a taxon with a parent
    Given I am on "/admin/taxonomies/"
    And I follow "Détails"
    And I follow "Nouveau taxon"
    And I fill in the following:
      | Code | space-opera |
      | Nom  | Space-Opéra |
    And I select "Science-fiction" from "Parent"
    When I press "Créer"
    Then I should see "a bien été créé"