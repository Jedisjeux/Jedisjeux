@managing_dealer_prices
Feature: Editing a dealer price
    In order to change information about a dealer price
    As an Administrator
    I want to be able to edit the dealer price

    Background:
        Given there is dealer "Philibert"
        And there is product "Puerto Rico"
        And there is product "Modern Art"
        And the dealer "Philibert" sold "Puerto Rico" product on "http://www.example.com/puerto-rico" page
        And the dealer "Philibert" sold "Modern Art" product on "http://www.example.com/modern-art" page
        And I am logged in as an administrator

    @ui
    Scenario: Renaming an existing dealer price
        Given I want to edit dealer price with page "http://www.example.com/puerto-rico"
        When I change its name as "Puerto Ricoche"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this dealer price with name "Puerto Ricoche" should appear in the website

