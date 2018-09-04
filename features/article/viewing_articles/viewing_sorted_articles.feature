@viewing_articles
Feature: Sorting listed articles
    In order to change the order in which articles are displayed
    As a Customer
    I want to be able to sort articles

    Background:
        Given there are default taxonomies for articles
        And there is an article "New millennium!" written by "kevin@example.com", published at "first day of January 2000"
        And there is an article "New year!" written by "kevin@example.com", published at "first day of January 2005"

    @ui
    Scenario: Sorting articles by their dates with descending order
        When I view newest articles
        Then I should see 2 articles in the list
        And I should see the article "New millennium!"
        But the first article on the list should have title "New year!"

    @ui
    Scenario: Sorting articles by their dates with ascending order
        When I view oldest articles
        Then I should see 2 articles in the list
        And I should see the article "New year!"
        But the first article on the list should have title "New millennium!"
