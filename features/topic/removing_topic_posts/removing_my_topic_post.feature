@removing_topic_posts
Feature: Removing my post as a customer
    In order to remove a post that I wrote
    As a Customer
    I want to be able to remove my post

    Background:
        Given there are default taxonomies for topics
        And there is a customer with email "kevin@example.com"
        And there is a topic with title "Awesome topic" written by "kevin@example.com"
        And I am logged in as a customer
        And I wrote a post to this topic

    @ui
    Scenario: Removing my post
        Given I want to remove this post
        Then I should be notified that it has been successfully deleted
