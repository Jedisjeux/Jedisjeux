@viewing_topics
Feature: Sorting listed topics
    In order to change the order in which topics are displayed
    As a Visitor
    I want to be able to sort topics

    Background:
        Given there are default taxonomies for topics
        And there is a customer with email "kevin@example.com"
        And there is a topic with title "Oldest topic" written by "kevin@example.com", created at "first day of January 2000"
        Given there is a topic with title "Newest topic" written by "kevin@example.com", created at "first day of January 2005"

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
