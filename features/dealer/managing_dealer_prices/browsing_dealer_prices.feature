@managing_dealer_prices
Feature: Browsing dealer prices
    In order to see all dealer prices in the website
    As an Administrator
    I want to browse dealer prices

    Background:
        Given there is dealer "Philibert"
        And there is a product "Puerto Rico"
        And there is a product "Modern Art"
        And the dealer "Philibert" sold "Puerto Rico" product on "http://www.example.com/puerto-rico" page
        And the dealer "Philibert" sold "Modern Art" product on "http://www.example.com/modern-art" page
        And I am logged in as an administrator

    @ui
    Scenario: Browsing dealer prices in website
        When I want to browse dealer prices
        Then there should be 2 dealer prices in the list
        And I should see the url "http://www.example.com/puerto-rico" in the list
