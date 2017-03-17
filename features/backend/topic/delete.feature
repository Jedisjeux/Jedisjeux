@ui @backend @topic @delete
Feature: Remove topics
  In order to manage topics
  As an admin
  I need to be able to remove topics

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
      | Jeux avec handicap             | forum/moi-je-dis-jeux | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a topic
    Given I am on "/admin/topics/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a topic with modal
    Given I am on "/admin/topics/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"