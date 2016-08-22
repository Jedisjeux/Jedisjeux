@ui @backend @taxon @index
Feature: List taxons
  In order to manage taxons
  As an admin
  I need to be able to list taxons

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code   | name   |
      | themes | Thèmes |
    And there are taxons:
      | parent | name            |
      | Thèmes | Science-fiction |
      | Thèmes | Fantastique |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: List taxons
    Given I am on "/admin/taxons/"
    When I follow "Thèmes"
    Then I should see "Science-fiction"
    And I should see "Fantastique"