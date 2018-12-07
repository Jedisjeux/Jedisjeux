@managing_topics
Feature: Deleting multiple topics
    In order to get rid of spam topics in an efficient way
    As an Administrator
    I want to be able to delete multiple topics at once

    Background:
        Given there is a topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And there is a topic with title "Liste des jeux à ajouter" written by "kevin@example.com"
        And there is a topic with title "Vos nouvelles acquisitions ludiques" written by "kevin@example.com"
        And I am a logged in administrator

    @ui @javascript
    Scenario: Deleting multiple topics at once
        Given I browse topics
        And I check the "Les parties jouées la veille" topic
        And I also check the "Liste des jeux à ajouter" topic
        And I delete them
        Then I should be notified that they have been successfully deleted
        And I should see a single topic in the list
        And I should see the topic "Vos nouvelles acquisitions ludiques" in the list
