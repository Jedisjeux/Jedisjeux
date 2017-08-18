@managing_dealer_prices
Feature: Browsing dealer prices
    In order to see all dealer prices in the website
    As an Administrator
    I want to browse dealer prices

    Background:
        Given there is dealer "Philibert"
        And there is product "Puerto Rico"
        And there is product "Modern Art"
        And the dealer "Philibert" sold "Puerto Rico" product priced at "3250"
        And the dealer "Philibert" sold "Modern Art" product priced at "2450"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing dealer prices in website
        When I want to browse dealer prices
        Then there should be 2 dealer prices in the list
        And I should see the price "€32.50" in the list
