@editing_topics
Feature: Removing my topic as a customer
    In order to remove a topic that I wrote
    As a Customer
    I want to be able to remove my topic

    Background:
        Given there are default taxonomies for topics
        And I am a logged in customer

    @ui
    Scenario: Removing my topic
        Given I wrote a topic with title "Les parties jouées la veille"
        When I remove this topic
        Then I should be notified that it has been successfully deleted
        And I should not see the topic "Les parties jouées la veille"

    @ui
    Scenario: Not being able to remove the topic of someone else
        Given there is a topic with title "Liste des jeux à ajouter" written by "kevin@example.com"
        Then I should not be able to remove this topic
