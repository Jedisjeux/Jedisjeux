@viewing_game_libraries
Feature: Viewing a game list
    In order to see the games a member selected
    As a Visitor
    I want to view one of their game lists

    Background:
        Given there is a product "Puerto Rico"
        And there is also a product "Modern Art"
        And there is a user "kevin@example.com"
        And this customer has a product list "Wishes"
        And this product list contains "Puerto Rico" product

    @ui
    Scenario: Viewing a game list
        When I check the game list this product list
        Then there should be 1 item in this list
        And I should see "Puerto Rico" in this list
