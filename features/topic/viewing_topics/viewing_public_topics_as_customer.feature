@viewing_topics
Feature: Viewing public topics
    In order to see topics
    As a Visitor
    I want to be able to browse topics

    Background:
        Given there are default taxonomies for topics
        And there is a public topic category "Public"
        And there is a private topic category "Private"
        And there is a customer with email "kevin@example.com"
        And there is a topic with title "Public topic" written by "kevin@example.com"
        And this topic belongs to "Public" category
        And there is a topic with title "Private topic" written by "kevin@example.com"
        And this topic belongs to "Private" category

    @ui
    Scenario: Viewing public topics
        When I want to browse topics
        Then I should see the topic "Public topic"
        But I should not see the topic "Private topic"
