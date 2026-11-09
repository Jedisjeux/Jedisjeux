@managing_article_reviews
Feature: Deleting a review of an article
    In order to get rid of inappropriate reviews
    As an Administrator
    I want to be able to delete the reviews of an article

    Background:
        Given there are default taxonomies for articles
        And there is an article "Essen 2017" written by "kevin@example.com"
        And customer "alice@example.com" rated this article 8
        And customer "bob@example.com" also rated this article 6
        And I am a logged in administrator

    @ui
    Scenario: Deleting a review of an article
        Given I want to browse the reviews of this article
        When I delete the review of "alice@example.com"
        Then I should be notified that it has been successfully deleted
        And there should be 1 review of this article in the list
