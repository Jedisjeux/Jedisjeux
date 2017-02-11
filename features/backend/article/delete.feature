@ui @backend @article @delete
Feature: Remove articles
  In order to manage articles
  As an admin
  I need to be able to remove articles

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                        |
      | articles/actualites | King of New York : Power Up! |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a article
    Given I am on "/admin/articles/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a article with modal
    Given I am on "/admin/articles/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"