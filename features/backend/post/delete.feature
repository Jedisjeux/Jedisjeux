@ui @backend @post @delete
Feature: Remove posts
  In order to manage posts
  As an admin
  I need to be able to remove posts

  Background:
    Given there are users:
      | email             | role       | password |
      | kevin@example.com | ROLE_USER  | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | forum  |
      | XYZ  | Réglons-ça      | forum  |
    And there are topics:
      | title                          | main_taxon            | author            |
      | Retour de Cannes jour par jour | forum/reglons-ca      | kevin@example.com |
    And there are posts:
      | topic                          | author            |
      | Retour de Cannes jour par jour | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a post
    Given I am on "/admin/posts/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a post with modal
    Given I am on "/admin/posts/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"