@managing_game_plays
Feature: Deleting a game play
    In order to get rid of deprecated game plays
    As an Administrator
    I want to be able to delete game plays

    Background:
        Given there is a product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com"
        And this product has one game play from customer "blue@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a game play
        Given I want to browse game plays
        When I delete game play of "Puerto Rico" played by "kevin@example.com"
        Then I should be notified that it has been successfully deleted
        And there should not be game play of "Puerto Rico" played by "kevin@example.com" anymore

    @ui
    Scenario: Deleting a game play with comments
        Given there is a product "Dream Factory"
        And this product has one game play from customer "kevin@example.com" with 2 comments
        And I want to browse game plays
        When I delete game play of "Dream Factory" played by "kevin@example.com"
        Then I should be notified that it has been successfully deleted
        And there should not be game play of "Dream Factory" played by "kevin@example.com" anymore
