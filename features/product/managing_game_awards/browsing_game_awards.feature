@managing_game_awards
Feature: Browsing game awards
    In order to see all game awards in the website
    As an Administrator
    I want to browse game awards

    Background:
        Given there is a game award "Spiel des Jahres"
        And there is also a game award "As d'or"
        And there is also a game award "Deutscher Spiele Preis"
        And I am a logged in administrator

    @ui
    Scenario: Browsing game awards in website
        When I want to browse game awards
        Then there should be 3 game awards in the list
        And I should see the game award "Spiel des Jahres" in the list
