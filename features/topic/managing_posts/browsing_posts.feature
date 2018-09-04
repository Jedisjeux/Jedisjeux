@managing_posts
Feature: Browsing posts
    In order to see all posts in the website
    As an Administrator
    I want to browse posts

    Background:
        Given there is a topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And this topic has a post added by customer "blue@example.com"
        And this topic has also a post added by customer "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing posts in website
        When I want to browse posts
        Then there should be 2 posts in the list
        And I should see the post added by customer "blue@example.com" on topic "Les parties jouées la veille" in the list
        And I should see the post added by customer "kevin@example.com" on topic "Les parties jouées la veille" in the list
