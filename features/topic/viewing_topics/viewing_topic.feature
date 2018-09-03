@viewing_topics
Feature: Viewing a topic details
    In order to see topic detailed and its posts
    As a Visitor
    I want to be able to view a single topic

    Background:
        Given there are default taxonomies for topics
        And there is a customer with email "kevin@example.com"

    @ui
    Scenario: Viewing a detailed page with topic's title
        Given there is a topic with title "Latest game plays" written by "kevin@example.com"
        When I check this topic's details
        Then I should see the topic title "Latest game plays"
