@managing_game_awards
Feature: Editing a game award
    In order to change information about a game award
    As an Administrator
    I want to be able to edit a game award

    Background:
        Given there is a game award "Spiel des Jahres"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing game award
        Given I want to edit "Spiel des Jahres" game award
        When I change its name as "As d'or"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And I should see the game award "As d'or" in the list
