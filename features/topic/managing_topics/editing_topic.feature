@managing_topics
Feature: Editing a topic
    In order to change information about a topic
    As an Administrator
    I want to be able to edit the topic

    Background:
        Given there is a customer with email "kevin@example.com"
        And there is a topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And there is a topic with title "Liste des jeux à ajouter" written by "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing topic
        Given I want to edit "Les parties jouées la veille" topic
        When I change its title as "Les parties jouées demain"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this topic with title "Les parties jouées demain" should appear in the website

    @ui
    Scenario: Changing body of an existing topic
        Given I want to edit "Les parties jouées la veille" topic
        When I change its body as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this topic body should be "This topic is so awesome."
