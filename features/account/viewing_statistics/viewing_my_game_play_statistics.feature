@account_statistics
Feature: Viewing my game play statistics
    In order to know how much I played over a year
    As a Customer
    I want to see statistics on my game plays

    Background:
        Given I am a logged in customer
        And there is a product "Puerto Rico"
        And there is also a product "Agricola"

    @ui
    Scenario: Seeing my game plays of a year
        Given I played product "Puerto Rico" 3 times in 2021
        And I played product "Agricola" once in 2021
        When I check my statistics of 2021
        Then I should see that I played 4 game plays
        And I should see that I played 2 different games
        And my most played games should be "Puerto Rico" with 3 and "Agricola" with 1

    @ui
    Scenario: Seeing only the game plays of the selected year
        Given I played product "Puerto Rico" 3 times in 2021
        And I played product "Agricola" 2 times in 2020
        When I check my statistics of 2020
        Then I should see that I played 2 game plays
        And I should see that I played 1 different game
        And my most played game should be "Agricola" with 2

    @ui
    Scenario: Not seeing the game plays of other customers
        Given I played product "Puerto Rico" once in 2021
        And customer "kevin@example.com" played product "Agricola" 5 times in 2021
        When I check my statistics of 2021
        Then I should see that I played 1 game play
        And my most played game should be "Puerto Rico" with 1

    @ui
    Scenario: Seeing a year without any game play
        Given I played product "Puerto Rico" once in 2021
        When I check my statistics of 2019
        Then I should be notified that I have no game play
