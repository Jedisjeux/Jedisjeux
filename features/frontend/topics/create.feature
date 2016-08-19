@ui @frontend @topic @create
Feature: Topic creation
  In order to manage topics
  As a user
  I need to be able to create new topics

  Background:
    Given there are following users:
      | email             | password | role      |
      | kevin@example.com | password | ROLE_USER |
    Given there are root taxons:
      | code  | name  |
      | forum | Forum |
    And I am logged in as user "kevin@example.com" with password "password"

  Scenario: Create new topic
    Given I am on "/forum/topics/"
    And I follow "Nouveau sujet"
    When I fill in the following:
      | Titre | Zoo Topic |
    And I fill in wysiwyg field "app_topic_mainPost_body" with "Here is my awesome topic message."
    And I press "Créer"
    Then I should see "Topic a bien été créé"