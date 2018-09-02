@adding_game_plays
Feature: Adding game play as a customer
    In order to share a game play with other customers
    As a Customer
    I want to be able to add game play

    Background:
        Given I am logged in as a customer
        And there is product "Puerto Rico"

    @ui
    Scenario: Adding game play with minimal information
        Given I want to add game play of this product
        When I specify its playing date as "yesterday"
        And I add it
        Then I should be notified that it has been successfully created

    @ui
    Scenario: Adding game play with full details
        Given I want to add game play of this product
        When I specify its playing date as "yesterday"
        And I specify its duration as "120"
        And I specify its player count as "5"
        And I add it
        Then I should be notified that it has been successfully created
