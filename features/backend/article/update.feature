@ui @backend @article @update
Feature: Edit articles
  In order to manage articles
  As an admin
  I need to be able to update articles

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
      | taxon               | title                        | author            |
      | articles/actualites | King of New York : Power Up! | admin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  @todo
  Scenario: Update an article
    Given I am on "/admin/articles/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"