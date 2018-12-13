@viewing_topics
Feature: Viewing topics last post
    In order to have an excerpt of each topics
    As a Visitor
    I want to be able to view last post of each topics

    Background:
        Given there are default taxonomies for topics
        And there is a topic with title "Awesome topic" written by "kevin@example.com"
        And customer "alfred@example.com" has left a comment "No way" on this topic
        And customer "teddy@example.com" has left a comment "Oh god!" on this topic

    @ui
    Scenario: Viewing topics last post
        When I want to browse topics
        Then I should see the topic last post "Oh god!"
        But I should not see the topic last post "No way"
