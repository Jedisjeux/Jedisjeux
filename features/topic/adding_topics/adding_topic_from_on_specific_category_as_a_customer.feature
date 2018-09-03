@adding_topics
Feature: Adding topic from a specific category as a customer
    In order to share a topic with other customers
    As a Customer
    I want to be able to add topic on a specific category

    Background:
        Given there are default taxonomies for topics
        And there is a public topic category "Games"
        And I am logged in as a customer

    @ui
    Scenario: Adding topic from a specific category as a customer
        Given I want to add topic on "Games" category
        When I leave a comment "Luke I am your father", titled "Important"
        And I add it
        Then I should be notified that it has been successfully created