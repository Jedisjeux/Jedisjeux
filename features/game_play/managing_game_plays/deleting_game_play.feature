@managing_game_plays
Feature: Deleting a game play
    In order to get rid of deprecated game plays
    As an Administrator
    I want to be able to delete game plays

    Background:
        Given there is customer with email "kevin@example.com"
        And there is customer with email "blue@example.com"
        And there is product "Puerto Rico"
        And there is game play of "Puerto Rico" played by "kevin@example.com"
        And there is game play of "Puerto Rico" played by "blue@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting a game play
        Given I want to browse game plays
        When I delete game play of "Puerto Rico" played by "kevin@example.com"
        Then I should be notified that it has been successfully deleted
