@managing_posts
Feature: Browsing posts
    In order to see all posts in the website
    As an Administrator
    I want to browse posts

    Background:
        Given there is customer with email "kevin@example.com"
        And there is customer with email "blue@example.com"
        And there is topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And this topic has a post added by customer "blue@example.com"
        And this topic has also a post added by customer "kevin@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing posts in website
        When I want to browse posts
        Then there should be 2 posts in the list
        And I should see the post added by customer "blue@example.com" on topic "Les parties jouées la veille" in the list
        And I should see the post added by customer "kevin@example.com" on topic "Les parties jouées la veille" in the list
