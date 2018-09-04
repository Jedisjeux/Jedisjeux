@editing_topic_posts
Feature: Editing my post as a customer
    In order to edit a post that I wrote
    As a Customer
    I want to be able to edit post

    Background:
        Given there are default taxonomies for topics
        And there is a topic with title "Awesome topic" written by "kevin@example.com"
        And I am a logged in customer
        And I wrote a post to this topic

    @ui
    Scenario: Editing my post
        Given I want to change this post
        When I change my comment as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
