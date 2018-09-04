@editing_article_comments
Feature: Editing my article comment as a customer
    In order to edit a comment that I wrote on an article
    As a Customer
    I want to be able to edit this comment

    Background:
        Given there are default taxonomies for articles
        And there is a customer with email "kevin@example.com"
        And there is an article "Latest game plays" written by "kevin@example.com"
        And I am logged in as a customer
        And I leaved a comment on this article

    @ui
    Scenario: Editing my post
        Given I want to change this comment
        When I change my comment as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
