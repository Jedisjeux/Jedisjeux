@ui @frontend @article @show
Feature: View articles
  In order to manage articles
  As a visitor
  I need to be able to view a article

  Background:
    Given there are users:
      | email             |
      | kevin@example.com |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                   | author            |
      | articles/actualites | Critique de Vroom Vroom | kevin@example.com |

  Scenario: Cannot access to an article with new status
    Given there are articles:
      | taxon               | title       | author            | status |
      | articles/actualites | New article | kevin@example.com | new    |
    When I am on "New article" article page
    Then the response status code should be 404

  Scenario: Cannot access to an article with pending review status
    Given there are articles:
      | taxon               | title                  | author            | status         |
      | articles/actualites | Pending review article | kevin@example.com | pending_review |
    When I am on "Pending review article" article page
    Then the response status code should be 404

  Scenario: Can access to an article with new status as a staff user
    Given there are articles:
      | taxon               | title       | author            | status |
      | articles/actualites | New article | kevin@example.com | new    |
    And there are users:
      | email             | role       | password |
      | staff@example.com | ROLE_STAFF | password |
    And I am logged in as user "staff@example.com" with password "password"
    When I am on "New article" article page
    Then the response status code should be 200
