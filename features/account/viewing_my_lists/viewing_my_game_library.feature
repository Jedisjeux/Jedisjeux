@account_lists
Feature: Viewing my game library
    In order to remember the games I own
    As a Customer
    I want to browse my game library

    Background:
        Given I am a logged in customer
        And my game library contains "Puerto Rico"
        And my game library also contains "Modern Art"

    @ui
    Scenario: Browsing my game library
        When I browse my game library
        Then there should be 2 items in this list
        And I should see "Puerto Rico" in this list
