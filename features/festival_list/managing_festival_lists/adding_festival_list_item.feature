@managing_festival_lists
Feature: Adding a game to a festival list
    In order to present the games of a festival
    As an Administrator
    I want to add games to a festival list

    Background:
        Given there are "Puerto Rico" and "Modern Art" products
        And there is festival list "Essen 2017"
        And festival list "Essen 2017" contains "Puerto Rico" product
        And I am a logged in administrator

    @ui
    Scenario: Adding a game to a festival list
        Given I want to add a game to festival list "Essen 2017"
        When I choose "Modern Art" product
        And I add this game
        Then I should be notified that it has been successfully created
        And I should see the game "Modern Art" in the festival list

    @ui
    Scenario: Trying to add a game twice to a festival list
        Given I want to add a game to festival list "Essen 2017"
        When I choose "Puerto Rico" product
        And I try to add this game
        Then I should be notified that this game is already in the festival list
