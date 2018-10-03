@viewing_articles
Feature: Viewing latest articles from a specific category
    In order to browse articles that interest me most
    As a Visitor
    I want to be able to view latest articles from a specific category

    Background:
        Given there are default taxonomies for articles
        And there are article categories "News" and "Reviews"
        And there is an article "Puerto Rico has been released" written by "kevin@example.com"
        And this article has "News" category
        And there is an article "Review of Puerto Rico" written by "kevin@example.com"
        And this article has "Reviews" category

    @ui
    Scenario: Viewing latest articles from a specific category
        Given there is an article "Great news" written by "kevin@example.com"
        And this article has "News" category
        When I check this article's details
        Then I should see the article "Puerto Rico has been released"
        But I should not see the article "Review of Puerto Rico"