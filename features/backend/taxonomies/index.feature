@backend @taxonomies @index
Feature: View list of taxonomies
  In order to manage taxonomies
  As an admin
  I need to be able to view all the taxonomies

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are taxonomies:
      | code       | name       |
      | themes     | Thèmes     |
      | mechanisms | Mécanismes |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of taxonomies
    When I am on "/admin/taxonomies/"
    Then I should see "Thèmes"
    And I should see "Mécanismes"