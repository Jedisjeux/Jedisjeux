@ui @frontend @post @edit
Feature: Edit Post
  In order to use forum
  As a user
  I need to be able to edit my posts

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    Given there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are topics:
      | name      |
      | Zoo Topic |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Update my post
    Given I am on "/topics/"
    And I follow "Lire le sujet"
    And I follow "Répondre au sujet"
    And I fill in wysiwyg field "app_post_body" with "Here is my awesome topic answer."
    And I press "Créer"
    And I follow "Modifier"
    And I fill in wysiwyg field "app_post_body" with "Here is my awesome topic answer edited."
    When I press "Mettre à jour"
    Then I should see "a bien été mis à jour"