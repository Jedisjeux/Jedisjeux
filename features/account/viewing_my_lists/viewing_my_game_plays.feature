@account_lists
Feature: Viewing my game plays
    In order to find the games I played
    As a Customer
    I want to browse my game plays

    Background:
        Given I am a logged in customer
        And there is a product "Puerto Rico"
        And this product has one game play written by me
        And there is also a product "Modern Art"
        And this product has a game play added by customer "kevin@example.com"

    @ui
    Scenario: Browsing only my game plays
        When I browse my game plays
        Then I should see "Puerto Rico" in this list
        But I should not see "Modern Art" in this list
