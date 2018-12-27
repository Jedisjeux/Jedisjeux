@managing_game_awards
Feature: Adding a new game award
    In order to use different awards
    As an Administrator
    I want to add a new game award to the website

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new game award with name
        Given I want to create a new game award
        When I name it "Spiel des Jahres"
        And I add it
        Then I should be notified that it has been successfully created
        And I should see the game award "Spiel des Jahres" in the list
