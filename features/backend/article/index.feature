@ui @backend @article @index
Feature: View list of articles
  In order to manage articles
  As an administrator
  I need to be able to view all the articles

  Background:
    Given init doctrine phpcr repository
    And there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code    | name       | parent   |
      | news    | Actualités | articles |
      | reviews | Critiques  | articles |
    And there are articles:
      | taxon               | title                        | author            |
      | articles/actualites | King of New York : Power Up! | admin@example.com |
      | articles/critiques  | Critique de Vroom Vroom      | admin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of articles
    When I am on "/admin/articles/"
    Then I should see "Critique de Vroom Vroom"
    And I should see "King of New York : Power Up!"

  Scenario: User with workflow role can manage articles
    Given there are users:
      | email                | role          | password |
      | workflow@example.com | ROLE_WORKFLOW | password |
    And I am logged in as user "workflow@example.com" with password "password"
    When I am on "/admin/articles/"
    Then the response status code should be 200

  Scenario: Staff user cannot manage articles
    Given there are users:
      | email                | role          | password |
      | staff@example.com | ROLE_STAFF | password |
    And I am logged in as user "staff@example.com" with password "password"
    When I am on "/admin/articles/"
    Then the response status code should be 403

  Scenario: Simple user cannot manage articles
    Given there are users:
      | email            | role      | password |
      | user@example.com | ROLE_USER | password |
    And I am logged in as user "user@example.com" with password "password"
    When I am on "/admin/articles/"
    Then the response status code should be 403