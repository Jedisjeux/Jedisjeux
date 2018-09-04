@removing_article_comments
Feature: Removing my comment on an article
    In order to remove a comment that I wrote
    As a Customer
    I want to be able to remove my comment

    Background:
        Given there are default taxonomies for topics
        And there is a customer with email "kevin@example.com"
        And there is an article "Latest game plays" written by "kevin@example.com"
        And I am a logged in customer
        And I leaved a comment on this article

    @ui
    Scenario: Removing my comment on an article
        Given I want to remove this comment
        Then I should be notified that it has been successfully deleted
