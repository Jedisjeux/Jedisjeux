@viewing_articles
Feature: Viewing articles
    In order to see articles
    As a Visitor
    I want to be able to browse articles

    Background:
        Given there are default taxonomies for articles
        And there is a customer with email "kevin@example.com"

    @ui
    Scenario: Viewing articles
        Given there is article "Awesome article" written by "kevin@example.com"
        And there is article "Bad article" written by "kevin@example.com"
        When I want to browse articles
        Then I should see the article "Awesome article"
        And I should see the article "Bad article"
