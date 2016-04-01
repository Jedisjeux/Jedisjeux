@backend @taxonomies @update
Feature: Edit taxonomies
  In order to manage taxonomies
  As an admin
  I need to be able to update taxonomies

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are taxonomies:
      | code   | name   |
      | themes | Thèmes |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a category
    Given I am on "/admin/taxonomies/"
    And I follow "Modifier"
    And I fill in the following:
      | Nom | Mécanismes |
    When I press "Mettre à jour"
    Then I should see "a bien été mis à jour"