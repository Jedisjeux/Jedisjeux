@viewing_articles
Feature: Allow viewing unpublished article details
    In order to view unpublished articles detailed information
    As an article manager
    I am able to view an article which is not published

    Background:
        Given there are default taxonomies for articles
        And there is a customer with email "kevin@example.com"
        And I am logged in as an article manager

    @ui
    Scenario: Viewing details of a article with new status
        When there is article "Awesome article" written by "kevin@example.com" with "new" status
        When I should be able to see this article's details

    @ui
    Scenario: Viewing details of a article with pending translation status
        When there is article "Awesome article" written by "kevin@example.com" with "pending translation" status
        Then I should be able to see this article's details

    @ui
    Scenario: Viewing details of a article with pending review status
        When there is article "Awesome article" written by "kevin@example.com" with "pending review" status
        Then I should be able to see this article's details

    @ui
    Scenario: Viewing details of a article with pending publication status
        When there is article "Awesome article" written by "kevin@example.com" with "pending publication" status
        Then I should be able to see this article's details
