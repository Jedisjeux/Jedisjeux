@editing_game_play_comments
Feature: Editing my article comment as a customer
    In order to edit a comment that I wrote on an article
    As a Customer
    I want to be able to edit this comment

    Background:
        Given there is product "Puerto Rico"
        And there is a customer with email "kevin@example.com"
        And this product has one game play from customer "kevin@example.com"
        And I am logged in as a customer
        And I leaved a comment on this game play

    @ui
    Scenario: Editing my post
        Given I want to change this comment
        When I change my comment as "This topic is so awesome."
        And I save my changes
        Then I should be notified that it has been successfully edited
