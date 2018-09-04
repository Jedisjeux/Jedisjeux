@managing_posts
Feature: Editing a post
    In order to change information about a post
    As an Administrator
    I want to be able to edit the post

    Background:
        Given there is a topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And this topic has a post added by customer "blue@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Changing body of an existing post
        When I want to edit this post
        And I change its body as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this post body should be "This topic is so awesome."
