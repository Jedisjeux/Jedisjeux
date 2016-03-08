@frontend @topics @create
Feature: Topic creation
  In order to manage topics
  As a user
  I need to be able to create new topics

  Background:
    Given there are following users:
      | username | password |
      | kevin | password |
    And user "kevin" has following roles:
      | ROLE_USER |
    And I am logged in as user "kevin" with password "password"

  Scenario: Create new topic
    Given I am on "/forum/topics/"
    And I follow "Nouveau sujet"
    When I fill in the following:
      | Titre | Zoo Topic |
      | Corps    |  <p>Lorem Ipsum...</p>             |
    And I press "Créer"
    Then I should see "Topic a bien été créé"
    And I should see "Nouveau sujet"
    And I should see "Lorem Ipsum"