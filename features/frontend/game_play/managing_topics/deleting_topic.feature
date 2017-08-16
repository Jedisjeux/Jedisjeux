@managing_topics
Feature: Deleting a topic
    In order to get rid of deprecated topics
    As an Administrator
    I want to be able to delete topics

    Background:
        Given there is customer with email "kevin@example.com"
        And there is customer with email "blue@example.com"
        And there is topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And there is topic with title "Liste des jeux à ajouter" written by "kevin@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting a topic
        Given I want to browse topics
        When I delete topic with title "Les parties jouées la veille"
        Then I should be notified that it has been successfully deleted
        And there should not be "Les parties jouées la veille" topic anymore

    @ui
    Scenario: Deleting a topic with posts
        Given customer "blue@example.com" has answered the "Les parties jouées la veille" topic
        And I want to browse topics
        When I delete topic with title "Les parties jouées la veille"
        Then I should be notified that it has been successfully deleted
        And there should not be "Les parties jouées la veille" topic anymore
