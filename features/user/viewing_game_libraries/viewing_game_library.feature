@viewing_game_libraries
Feature: Viewing a game library
    In order to see games owned by a customer
    As a visitor
    I want to view his game library

    Background:
        Given there is a user "kevin@example.com"
        And this user has a game library
        And this user has "Puerto Rico" in his game library
        And this user has "Modern Art" in his game library

    @ui
    Scenario: Viewing a game library
        When I check his game library's details
        Then there should be 2 games in the game library
