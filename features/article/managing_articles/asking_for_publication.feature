@managing_articles
Feature: Asking for publication
    In order to publish articles
    As a reviewer
    I need to be able to ask for a publication

    Background:
        Given there are default taxonomies for articles
        And there is a publisher "yoda@example.com"
        And there is a publisher "god@example.com"
        And there is an article "King of New York : Power Up!" written by "anakin@example.com" with "pending_review" status
        And I am a logged in reviewer

    @ui
    Scenario: Asking for an article publication as a reviewer
        Given I want to edit "King of New York : Power Up!" article
        When I ask for a publication
        Then I should be notified that it has been successfully edited
        And this article with title "King of New York : Power Up!" should have "pending publication" status
        And customer "yoda@example.com" should have received a notification for article "King of New York : Power Up!"
        And customer "god@example.com" should also have received a notification for article "King of New York : Power Up!"
