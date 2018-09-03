@managing_dealer_prices
Feature: Deleting a dealer price
    In order to get rid of deprecated dealer prices
    As an Administrator
    I want to be able to delete dealer prices

    Background:
        Given there is dealer "Philibert"
        And there is a product "Puerto Rico"
        And there is a product "Modern Art"
        And the dealer "Philibert" sold "Puerto Rico" product on "http://www.example.com/puerto-rico" page
        And the dealer "Philibert" sold "Modern Art" product on "http://www.example.com/modern-art" page
        And I am logged in as an administrator

    @ui
    Scenario: Deleting a dealer price
        Given I want to browse dealer prices
        When I delete dealer price with page "http://www.example.com/puerto-rico"
        Then I should be notified that it has been successfully deleted
        And there should not be dealer price with page "http://www.example.com/puerto-ric" anymore
