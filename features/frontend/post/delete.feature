@ui @frontend @post @delete
Feature: Remove posts
  In order to manage posts
  As a user
  I need to be able to remove my posts

  Background:
    Given there are users:
      | email                    | role      | password |
      | kevin@example.com        | ROLE_USER | password |
      | topic.author@example.com | ROLE_USER | password |
    And there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | forum  |
      | XYZ  | Réglons-ça      | forum  |
    And there are topics:
      | title                          | main_taxon       | author                   |
      | Retour de Cannes jour par jour | forum/reglons-ca | topic.author@example.com |
    And there are posts:
      | topic                          | author            |
      | Retour de Cannes jour par jour | kevin@example.com |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Remove a post
    Given I am on "/topics/"
    And I follow "Retour de Cannes jour par jour"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a post with modal
    Given I am on "/topics/"
    And I follow "Retour de Cannes jour par jour"
    When I press "Supprimer"
    And I wait until modal is visible
    And I follow "Supprimer"
    Then I should see "a bien été supprimé"