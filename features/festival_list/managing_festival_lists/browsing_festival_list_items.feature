@managing_festival_lists
Feature: Browsing the games of a festival list
    In order to see the games of a festival list
    As an Administrator
    I want to browse the games of a festival list

    Background:
        Given there are "Puerto Rico" and "Modern Art" products
        And there is also a product "Ticket to Ride"
        And there is festival list "Essen 2017"
        And festival list "Essen 2017" contains "Puerto Rico" product
        And festival list "Essen 2017" contains "Modern Art" product
        And there is festival list "Cannes 2018"
        And festival list "Cannes 2018" contains "Ticket to Ride" product
        And I am a logged in administrator

    @ui
    Scenario: Browsing only the games of the festival list
        When I want to browse the games of festival list "Essen 2017"
        Then there should be 2 games in the festival list
        And I should see the game "Puerto Rico" in the festival list
        But I should not see the game "Ticket to Ride" in the festival list
