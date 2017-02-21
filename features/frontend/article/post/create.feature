@ui @frontend @article @comment @create
Feature: Article comment creation
  In order to comment articles
  As a user
  I need to be able to create new comments on an article

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    And there are root taxons:
      | code     | name     |
      | articles | Articles |
    And there are taxons:
      | code | name       | parent   |
      | news | Actualités | articles |
    And there are articles:
      | taxon               | title                        |
      | articles/actualites | Critique de Vroom Vroom      |
    And I am logged in as user "kevin@example.com" with password "password"

  @javascript
  Scenario: Create new comment
    Given I am on "/articles/"
    And I follow "Critique de Vroom Vroom"
    And I follow "Commenter l'article"
    And I fill in wysiwyg field "app_post_body" with "Here is my awesome comment."
    When I press "Créer"
    Then I should see "a bien été créé"