@managing_topics
Feature: Browsing topics
    In order to see all topics in the website
    As an Administrator
    I want to browse topics

    Background:
        Given there is a topic with title "Les parties jouées la veille" written by "kevin@example.com"
        And there is a topic with title "Liste des jeux à ajouter" written by "kevin@example.com"
        And there is a topic with title "Vos nouvelles acquisitions ludiques" written by "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing topics in website
        When I want to browse topics
        Then there should be 3 topics in the list
        And I should see the topic "Les parties jouées la veille" in the list
