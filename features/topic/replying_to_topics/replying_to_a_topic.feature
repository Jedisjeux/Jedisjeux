@replying_to_topics
Feature: Replying to topics
    In order to reply to topics
    As a Visitor
    I want to be able to create a new post

    Background:
        Given there are default taxonomies for topics
        And there is customer with email "kevin@example.com"
        And there is topic with title "Awesome topic" written by "kevin@example.com"
        And I am logged in as a customer

    @ui
    Scenario: Replying to a topic
        Given I want to reply to this topic
        When I leave a comment "Great topic for every boardgame players."
        And I add it
        Then I should be notified that it has been successfully created
