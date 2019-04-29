@managing_articles
Feature: Adding a new article
    In order to extend articles database
    As an Administrator
    I want to add a new article to the website

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new article with title as an administrator
        Given I want to create a new article
        When I specify his title as "Le Jedisjeux nouveau est arrivé"
        And I add it
        Then I should be notified that it has been successfully created
        And the article "Le Jedisjeux nouveau est arrivé" should appear in the website

    @ui
    Scenario: Adding a new article for a product with title as an administrator
        Given there is a product "Puerto Rico"
        And customer "kevin@example.com" has subscribed to this product to be notified about new articles
        And I want to create a new article for this product
        When I specify his title as "Puerto rico rocks!"
        And I add it
        Then I should be notified that it has been successfully created
        And the article "Puerto rico rocks!" should appear in the website
