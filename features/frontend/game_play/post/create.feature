@ui @frontend @gamePlay @comment @create
Feature: Game-play comment creation
  In order to comment game plays
  As a user
  I need to be able to create new comments on a game play

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    And there are products:
      | name          |
      | Lewis & Clark |
    And there are game plays:
      | product       | author            |
      | Lewis & Clark | kevin@example.com |
    And I am logged in as user "kevin@example.com" with password "password"

  @javascript
  Scenario: Create new comment
    Given I am on "/mon-compte/parties"
    And I follow "Lewis & Clark"
    And I follow "Commenter la partie"
    And I fill in wysiwyg field "app_post_body" with "Here is my awesome comment."
    When I press "Créer"
    Then I should see "a bien été créé"