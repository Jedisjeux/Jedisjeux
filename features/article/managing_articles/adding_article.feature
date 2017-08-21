@managing_articles
Feature: Adding a new article
  In order to extend articles database
  As an Administrator
  I want to add a new article to the website

  Background:
    Given I am logged in as an administrator

  @ui
  Scenario: Adding a new article with title
    Given I want to create a new article
    When I specify his title as "Le Jedisjeux nouveau est arrivé"
    When I add it
    Then I should be notified that it has been successfully created
    And the article "Le Jedisjeux nouveau est arrivé" should appear in the website