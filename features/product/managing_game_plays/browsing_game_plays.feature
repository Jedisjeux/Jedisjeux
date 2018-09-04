@managing_game_plays
Feature: Browsing game plays
    In order to see all game plays in the website
    As an Administrator
    I want to browse game plays

    Background:
        Given there is a customer with email "kevin@example.com"
        And there is a customer with email "blue@example.com"
        And there is a product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com"
        And this product has one game play from customer "blue@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing game plays in website
        When I want to browse game plays
        Then there should be 2 game plays in the list
        And I should see the game play of "Puerto Rico" played by "kevin@example.com" in the list
