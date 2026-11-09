@account_lists
Feature: Viewing my game lists
    In order to organise the games I care about
    As a Customer
    I want to browse my game lists and their games

    Background:
        Given there is a product "Puerto Rico"
        And there is also a product "Modern Art"
        And there is also a product "Ticket to Ride"
        And I am a logged in customer
        And this customer has a product list "Wishes"
        And this product list contains "Puerto Rico" product
        And this product list also contains "Modern Art" product
        And this customer has also a product list "To try"
        And this product list contains "Ticket to Ride" product

    @ui
    Scenario: Browsing my game lists
        When I browse my game lists
        Then I should see "Wishes" in this list
        And I should see "To try" in this list

    @ui
    Scenario: Browsing the games of one of my game lists
        When I check my game list this product list
        Then there should be 1 item in this list
        And I should see "Ticket to Ride" in this list
        But I should not see "Puerto Rico" in this list
