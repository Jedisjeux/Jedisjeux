@viewing_topics
Feature: Viewing topics
    In order to see topics
    As a Visitor
    I want to be able to browse topics

    Background:
        Given there are default taxonomies for topics
        And there is customer with email "kevin@example.com"

    @ui
    Scenario: Viewing topics
        Given there is topic with title "Awesome topic" written by "kevin@example.com"
        And there is topic with title "Bad topic" written by "kevin@example.com"
        When I want to browse topics
        Then I should see the topic "Awesome topic"
        And I should see the topic "Bad topic"
