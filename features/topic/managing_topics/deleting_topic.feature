@managing_topics
Feature: Deleting a topic
    In order to get rid of deprecated topics
    As an Administrator
    I want to be able to delete topics

    Background:
        Given there is a topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And there is a topic with title "Liste des jeux à ajouter" written by "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a topic
        Given I want to browse topics
        When I delete topic with title "Les parties jouées la veille"
        Then I should be notified that it has been successfully deleted
        And there should not be "Les parties jouées la veille" topic anymore
