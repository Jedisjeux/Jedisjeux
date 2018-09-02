@viewing_articles
Feature: Viewing a article details
    In order to see articles detailed information
    As a Visitor
    I want to be able to view a single article

    Background:
        Given there are default taxonomies for articles
        And there is a customer with email "kevin@example.com"

    @ui
    Scenario: Viewing a detailed page with article's title
        Given there is article "Awesome article" written by "kevin@example.com"
        When I check this article's details
        Then I should see the article title "Awesome article"
