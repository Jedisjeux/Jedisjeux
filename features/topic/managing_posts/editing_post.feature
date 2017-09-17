@managing_posts
Feature: Editing a post
    In order to change information about a post
    As an Administrator
    I want to be able to edit the post

    Background:
        Given there is customer with email "kevin@example.com"
        And there is customer with email "blue@example.com"
        And there is topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And this topic has a post added by customer "blue@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Changing body of an existing post
        When I want to edit this post
        And I change its body as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this post body should be "This topic is so awesome."
