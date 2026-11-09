@managing_game_awards
Feature: Deleting a game award
    In order to get rid of deprecated game awards
    As an Administrator
    I want to be able to delete game awards

    Background:
        Given there is a game award "Spiel des Jahres"
        And there is also a game award "As d'Or"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a game award
        Given I want to browse game awards
        When I delete the game award "Spiel des Jahres"
        Then I should be notified that it has been successfully deleted
        And there should not be the game award "Spiel des Jahres" anymore
