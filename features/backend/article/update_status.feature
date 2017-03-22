@ui @backend @article @update_status
Feature: Update article status
  In order to manage articles
  As an admin
  I need to be able to update article status

  Background:
    Given there are users:
      | email                  | role            | password |
      | admin@example.com      | ROLE_ADMIN      | password |
      | translator@example.com | ROLE_TRANSLATOR | password |
      | reviewer@example.com   | ROLE_REVIEWER   | password |
      | publisher@example.com  | ROLE_PUBLISHER  | password |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code    | name       | parent   |
      | news    | Actualités | articles |
      | reviews | Critiques  | articles |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Ask for review
    Given there are articles:
      | taxon               | title                        | status | author            |
      | articles/actualites | King of New York : Power Up! | new    | admin@example.com |
    And I am on "/article/king-of-new-york-power-up"
    When I press "Demander une relecture"
    Then I should see "a bien été mis à jour"
    And I should see "Cet article est En relecture."
    And there is a notification to "reviewer@example.com" for "King of New York : Power Up!" article

  Scenario: Ask for publication
    Given there are articles:
      | taxon               | title                        | status         | author            |
      | articles/actualites | King of New York : Power Up! | pending_review | admin@example.com |
    And I am on "/article/king-of-new-york-power-up"
    When I press "Demander la publication"
    Then I should see "a bien été mis à jour"
    And I should see "Cet article est Prêt à publier."
    And there is a notification to "publisher@example.com" for "King of New York : Power Up!" article