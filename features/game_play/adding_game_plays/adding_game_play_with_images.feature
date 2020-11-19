@adding_game_plays
Feature: Adding game play as a customer
    In order to share a game play with other customers
    As a Customer
    I want to be able to add game play

    Background:
        Given I am a logged in customer
        And there is a product "Puerto Rico"

    @ui @javascript @todo
    Scenario: Adding game play with images
        Given I want to add game play of this product
        When I specify its playing date as "yesterday"
        And I attach the "game_plays/5b2d42e75dc282.52843500.JPG" image
        And I add it
        Then I should be notified that it has been successfully created
