@backend @taxonomies @create
Feature: Creates taxonomies
  In order to manage taxonomies
  As an admin
  I need to be able to create taxonomies

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a taxonomy
    Given I am on "/admin/taxonomies/"
    And I follow "Nouvelle taxonomie"
    And I fill in the following:
      | Code | themes |
      | Nom  | Thèmes |
    When I press "Créer"
    Then I should see "a bien été créé"