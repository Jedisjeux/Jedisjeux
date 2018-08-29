@viewing_game_plays
Feature: Viewing game plays with comments
    In order to see game plays with comments
    As a Visitor
    I want to be able to browse game plays

    Background:
        Given there is customer with email "kevin@example.com"
        And there is product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com" with 2 comments
        And there is product "Modern Art"
        And this product has one game play from customer "kevin@example.com" with 2 comments
        And there is product "Euphrat & Tigris"
        And this product has one game play from customer "kevin@example.com"

    @ui
    Scenario: Viewing game plays with comments
        Given I want to browse game plays
        Then I should see the game play from product "Puerto Rico"
        And I should see the game play from product "Modern Art"
        But I should not see the game play from product "Euphrat & Tigris"
