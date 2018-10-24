@managing_articles
Feature: Publishing article
    In order to publish articles
    As a publisher
    I need to be able to publish an article

    Background:l
        Given there are default taxonomies for articles
        And there is an article "King of New York : Power Up!" written by "anakin@example.com" with "pending_publication" status
        And I am a logged in publisher

    @ui
    Scenario: Publishing an article as a publisher
        Given I want to edit "King of New York : Power Up!" article
        When I publish it
        Then I should be notified that it has been successfully edited
        And this article with title "King of New York : Power Up!" should have "published" status
