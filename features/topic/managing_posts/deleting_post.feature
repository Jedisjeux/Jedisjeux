@managing_posts
Feature: Deleting a post
    In order to get rid of deprecated posts
    As an Administrator
    I want to be able to delete posts

    Background:
        Given there is customer with email "kevin@example.com"
        And there is customer with email "blue@example.com"
        And there is topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And this topic has a post added by customer "blue@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting a post
        Given I want to browse posts
        When I delete this post
        Then I should be notified that it has been successfully deleted
        And there should not be post added by customer "blue@example.com" on topic "Les parties jouées la veille" anymore
