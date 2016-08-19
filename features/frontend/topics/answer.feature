@ui @frontend @topic @answer
Feature: Topic answer
  In order to use forum
  As a user
  I need to be able to answer to a topic

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

  Scenario: Answer to a topic
    Given I am on "/forum/topics/"
    And I follow "Lire le sujet"
    And I follow "Répondre au sujet"
    When I fill in wysiwyg field "app_post_body" with "Here is my awesome topic answer."
    And I press "Créer"
    Then I should see "a bien été créé"