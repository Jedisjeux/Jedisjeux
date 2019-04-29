@managing_articles
Feature: Publishing article
    In order to publish articles
    As a publisher
    I need to be able to publish an article

    Background:l
        Given there are default taxonomies for articles
        And there is a product "Puerto Rico"
        And this product has an article titled "Puerto Rico rocks!" written by customer "anakin@example.com" with "pending_publication" status
        And I am a logged in publisher

    @ui
    Scenario: Publishing an article as a publisher
        Given I want to edit "Puerto Rico rocks!" article
        When I publish it
        Then I should be notified that it has been successfully edited
        And this article with title "King of New York : Power Up!" should have "published" status

    @ui
    Scenario: Notifying product subscribers about new article
        Given customer "kevin@example.com" has subscribed to this product to be notified about new articles
        And I want to edit "Puerto Rico rocks!" article
        When I publish it
        Then customer "kevin@example.com" should have received a notification for article "Puerto Rico rocks!"
