@editing_topics
Feature: Editing my topic as a customer
    In order to edit a topic that I wrote
    As a Customer
    I want to be able to edit topic

    Background:
        Given there are default taxonomies for topics
        And I am logged in as a customer
        And I wrote a topic with title "Les parties jouées la veille"

    @ui
    Scenario: Editing my topic
        Given I want to change this topic
        When I change my comment as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
