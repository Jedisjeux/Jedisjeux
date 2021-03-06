@viewing_articles
Feature: Viewing articles from a specific category
    In order to browse articles that interest me most
    As a Visitor
    I want to be able to view articles from a specific category

    Background:
        Given there are default taxonomies for articles
        And there are article categories "News" and "Reviews"
        And there is an article "Puerto Rico has been released" written by "kevin@example.com"
        And this article has "News" category
        And there is an article "Review of Puerto Rico" written by "kevin@example.com"
        And this article has "Reviews" category

    @ui
    Scenario: Viewing articles from a specific category
        When I browse articles from category "News"
        Then I should see the article "Puerto Rico has been released"
        But I should not see the article "Review of Puerto Rico"
