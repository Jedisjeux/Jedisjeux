@ui @frontend @topic @reply
Feature: Topic reply
  In order to use forum
  As a user
  I need to be able to reply to a topic

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    Given there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | forum  |
      | XYZ  | Réglons-ça      | forum  |
    And there are topics:
      | name      | author            | main_taxon            |
      | Zoo Topic | kevin@example.com | forum/moi-je-dis-jeux |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Reply to a topic
    Given I am on "/topics/"
    And I follow "Lire le sujet"
    And I follow "Répondre au sujet"
    When I fill in wysiwyg field "app_post_body" with "Here is my awesome topic answer."
    And I press "Créer"
    Then I should see "a bien été créé"