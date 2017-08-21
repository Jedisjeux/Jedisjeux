@managing_products
Feature: Browsing products
    In order to see all products in the website
    As an Administrator or a Redactor
    I want to browse products

    Background:
        Given there is product "Puerto Rico"
        And there is product "Modern Art"
        And there is product "Age of Steam"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing products in website
        When I want to browse products
        Then there should be 3 products in the list
        And I should see the product "Puerto Rico" in the list
