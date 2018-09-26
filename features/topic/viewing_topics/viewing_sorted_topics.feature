@viewing_topics
Feature: Sorting listed topics
    In order to change the order in which topics are displayed
    As a Visitor
    I want to be able to sort topics

    Background:
        Given there are default taxonomies for topics
        And there is a topic with title "Oldest topic" written by "kevin@example.com", created "50 days ago"
        And there is a topic with title "Newest topic" written by "kevin@example.com", created "today"

    @ui
    Scenario: Sorting topics by their dates with descending order
        When I view newest topics
        Then I should see 2 topics in the list
        And I should see the topic "Oldest topic"
        But the first topic on the list should be "Newest topic"

    @ui
    Scenario: Sorting topics by their dates with ascending order
        When I view oldest topics
        Then I should see 2 topics in the list
        And I should see the topic "Newest topic"
        But the first topic on the list should be "Oldest topic"

    @ui
    Scenario: Topic with recent posts should be first while sorting topics by their dates with descending order
        Given there is a topic with title "Topic with recent post" written by "kevin@example.com", created "5 years ago"
        And this topic has a post added by customer "marty.macfly@example.com", created "today 14:00"
        When I view newest topics
        Then I should see 3 topics in the list
        And I should see the topic "Newest topic"
        And I should see the topic "Oldest topic"
        But the first topic on the list should be "Topic with recent post"
