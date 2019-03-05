@managing_articles
Feature: Adding a new article with images
    In order to extend articles database
    As an Administrator
    I want to add a new article with images to the website

    Background:
        Given I am a logged in administrator

    @ui @javascript
    Scenario: Adding a new article with a main image
        Given I want to create a new article
        When I specify his title as "Le Jedisjeux nouveau est arrivé"
        And I attach the "articles/jedisjeux-nouveau.png" image
        And I add it
        Then I should be notified that it has been successfully created
        And the article "Le Jedisjeux nouveau est arrivé" should have a main image
