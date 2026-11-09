@managing_festival_lists
Feature: Removing a game from a festival list
    In order to fix the games of a festival
    As an Administrator
    I want to remove games from a festival list

    Background:
        Given there are "Puerto Rico" and "Modern Art" products
        And there is festival list "Essen 2017"
        And festival list "Essen 2017" contains "Puerto Rico" product
        And festival list "Essen 2017" contains "Modern Art" product
        And I am a logged in administrator

    @ui
    Scenario: Removing a game from a festival list
        Given I want to browse the games of festival list "Essen 2017"
        When I remove the game "Puerto Rico" from the festival list
        Then I should be notified that it has been successfully deleted
        And there should be 1 game in the festival list
        And I should not see the game "Puerto Rico" in the festival list
