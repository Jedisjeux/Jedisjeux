@removing_game_play_comments
Feature: Removing my comment on an article
    In order to remove a comment that I wrote
    As a Customer
    I want to be able to remove my comment

    Background:
        Given there is a product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com"
        And I am a logged in customer
        And I leaved a comment on this game play

    @ui
    Scenario: Removing my comment on a game play
        Given I want to remove this comment
        Then I should be notified that it has been successfully deleted
