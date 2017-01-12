@ui @frontend @topic @edit
Feature: Edit Topic
  In order to use forum
  As a user
  I need to be able to edit my topics

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

  Scenario: Update my topic
    Given I am on "/topics/"
    And I follow "Lire le sujet"
    And I follow "Modifier"
    When I press "Mettre à jour"
    Then I should see "a bien été mis à jour"