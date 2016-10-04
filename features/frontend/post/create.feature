@ui @frontend @post @create
Feature: Post creation
  In order to use forum
  As a user
  I need to be able to create new posts

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    Given there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | Forum  |
    And there are topics:
      | title              | main-taxon      |
      | Jeux avec handicap | Moi je dis jeux |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Create new post
    Given I am on "/forum/topics/"
    And I follow "Lire le sujet"
    And I follow "Répondre au sujet"
    And I fill in wysiwyg field "app_post_body" with "Here is my awesome topic response message."
    When I press "Créer"
    Then I should see "a bien été créé"