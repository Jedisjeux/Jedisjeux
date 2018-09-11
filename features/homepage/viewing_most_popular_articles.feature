@viewing_articles_on_homepage
Feature: Viewing a most popular article list
    In order to be up-to-date with the best articles to read
    As a Visitor
    I want to be able to view a most popular article list

    Background:
        Given there are default taxonomies for articles
        And there is an article "Shogun Review" written by "kevin@example.com"
        And this article has been viewed 60 times
        And there is also an article "New Patchwork Video" written by "krissou@example.com"
        And this article has been viewed 50 times
        And there is also an article "New Puerto Rico Video" written by "krissou@example.com"
        And this article has been viewed 40 times
        And there is also an article "New Modern Art Video" written by "krissou@example.com"
        And this article has been viewed 30 times
        And there is also an article "Essen 2018" written by "cyril@example.com"
        And this article has been viewed 20 times
        And there is also an article "Old Video" written by "krissou@example.com"
        And this article has been viewed 10 times

    @ui
    Scenario: Viewing most popular articles
        When I check most popular articles
        Then I should see 5 articles in the most popular articles list
        And I should see the article "Shogun Review" in the most popular articles list
        And I should see the article "Essen 2018" in the most popular articles list
        And I should see the article "New Patchwork Video" in the most popular articles list
        And I should see the article "New Puerto Rico Video" in the most popular articles list
        And I should see the article "New Modern Art Video" in the most popular articles list
        But I should not see the article "Old Video" in the most popular articles list
