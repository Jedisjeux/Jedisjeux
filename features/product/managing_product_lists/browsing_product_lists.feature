@managing_product_lists
Feature: Browsing product lists
    In order to see all product lists in the website
    As an Administrator
    I want to browse product lists

    Background:
        Given there is customer with email "kevin@example.com"
        And this customer has a product list "My favorite games"
        And this customer has a product list "My game library"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing product lists in website
        When I want to browse product lists
        Then there should be 2 product lists in the list
        And I should see the product list "My favorite games" in the list
        And I should also see the product list "My game library" in the list
