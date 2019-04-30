@managing_articles
Feature: Asking for review
    In order to publish my articles
    As a redactor
    I need to be able to ask for a review

    Background:
        Given there are default taxonomies for articles
        And there is a reviewer "obiwan@example.com"
        And there is a reviewer "luke@example.com"
        And I am a logged in redactor
        And I wrote an article "King of New York : Power Up!" with "new" status

    @ui
    Scenario: Asking for an article review as a redactor
        Given I want to edit "King of New York : Power Up!" article
        When I ask for a review
        Then I should be notified that it has been successfully edited
        And this article with title "King of New York : Power Up!" should have "pending review" status
        And customer "obiwan@example.com" should have received a notification for article "King of New York : Power Up!"
        And customer "luke@example.com" should have received a notification for article "King of New York : Power Up!"
