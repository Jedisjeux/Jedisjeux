@editing_game_plays
Feature: Editing game play as a customer
    In order to edit my game play information
    As a Customer
    I want to be able to edit game play

    Background:
        Given I am logged in as a customer
        And there is a product "Puerto Rico"
        And this product has one game play written by me

    @ui
    Scenario: Changing my game play duration
        Given I want to edit this game play
        When I change its duration as "180"
        And I save my changes
        Then I should be notified that it has been successfully edited

    @ui
    Scenario: Changing my game play player count
        Given I want to edit this game play
        When I change its player count as "5"
        And I save my changes
        Then I should be notified that it has been successfully edited