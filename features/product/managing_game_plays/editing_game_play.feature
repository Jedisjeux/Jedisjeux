@managing_game_plays
Feature: Editing a game play
    In order to change information about a game play
    As an Administrator
    I want to be able to edit the game play

    Background:
        Given there is customer with email "kevin@example.com"
        And there is customer with email "blue@example.com"
        And there is product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com"
        And this product has one game play from customer "blue@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Changing played at of an existing game play
        Given I want to edit game play of "Puerto Rico" played by "kevin@example.com"
        When I change its played at as "2016-12-03"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this game play should be played at "2016-12-03"

    @ui
    Scenario: Changing duration of an existing game play
        Given I want to edit game play of "Puerto Rico" played by "kevin@example.com"
        When I change its duration as 80
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this game play duration should be 80