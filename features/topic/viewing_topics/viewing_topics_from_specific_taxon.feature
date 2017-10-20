@viewing_topics
Feature: Viewing topics from specific taxon
    In order to see topics
    As a Visitor
    I want to be able to browse topics from specific taxon

    Background:
        Given there are default taxonomies for topics
        And there are topic categories "Tatoïne" and "Playing Games"
        And there is customer with email "kevin@example.com"
        And there is topic with title "Awesome topic" written by "kevin@example.com"
        And this topic belongs to "Playing Games" category
        And there is topic with title "Bad topic" written by "kevin@example.com"
        And this topic belongs to "Tatoïne" category

    @ui
    Scenario: Viewing topics from specific taxon
        When I want to browse topics from taxon "Playing Games"
        Then I should see the topic "Awesome topic"
        But I should not see the topic "Bad topic"
