@managing_article_reviews
Feature: Reviewing an article
    In order to give my opinion about an article
    As an Administrator
    I want to review an article

    Background:
        Given there are default taxonomies for articles
        And there is an article "Essen 2017" written by "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Reviewing an article
        Given I want to review this article
        When I rate it 8
        And I comment it "Very complete."
        And I add it
        Then I should be notified that it has been successfully created
        And there should be 1 review of this article in the list
