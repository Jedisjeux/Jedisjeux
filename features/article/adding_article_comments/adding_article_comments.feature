@adding_article_comments
Feature: Adding article comments
    In order to react on articles
    As a Visitor
    I want to be able to add a comment

    Background:
        Given there are default taxonomies for articles
        And there is an article "Latest game plays" written by "kevin@example.com"
        And I am a logged in customer

    @ui
    Scenario: Adding comment to an article
        Given I want to react on this article
        When I leave a comment "Great article for every boardgame players."
        And I add it
        Then I should be notified that it has been successfully created
        And this article should have one comment
