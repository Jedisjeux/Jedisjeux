@managing_articles
Feature: Adding a new article
    In order to extend articles database
    As an Administrator or a Redactor
    I want to add a new article to the website

    @ui
    Scenario: Adding a new article with title as an administrator
        Given I am a logged in administrator
        And I want to create a new article
        When I specify his title as "Le Jedisjeux nouveau est arrivé"
        And I add it
        Then I should be notified that it has been successfully created
        And the article "Le Jedisjeux nouveau est arrivé" should appear in the website

    @ui
    Scenario: Adding a new article with title as a redactor
        Given I am a logged in redactor
        And I want to create a new article
        When I specify his title as "Le Jedisjeux nouveau est arrivé"
        When I add it
        Then I should be notified that it has been successfully created
        And the article "Le Jedisjeux nouveau est arrivé" should appear in the website
