@viewing_articles_on_homepage
Feature: Viewing a latest article list
    In order to be up-to-date with the newest articles
    As a Visitor
    I want to be able to view a latest article list

    Background:
        Given there are default taxonomies for articles
        And there is an article "Shogun Review" written by "kevin@example.com", published at "1 days ago"
        And there is also an article "Essen 2018" written by "cyril@example.com", published at "2 days ago"
        And there is also an article "New Patchwork Video" written by "krissou@example.com", published at "3 days ago"
        And there is also an article "New Puerto Rico Video" written by "krissou@example.com", published at "4 days ago"
        And there is also an article "New Modern Art Video" written by "krissou@example.com", published at "5 days ago"
        And there is also an article "Old Video" written by "krissou@example.com", published at "6 days ago"

    @ui
    Scenario: Viewing latest articles
        When I check latest articles
        Then I should see 5 articles in the latest articles list
        And I should see the article "Shogun Review" in the latest articles list
        And I should see the article "Essen 2018" in the latest articles list
        And I should see the article "New Patchwork Video" in the latest articles list
        And I should see the article "New Puerto Rico Video" in the latest articles list
        And I should see the article "New Modern Art Video" in the latest articles list
        But I should not see the article "Old Video" in the latest articles list
