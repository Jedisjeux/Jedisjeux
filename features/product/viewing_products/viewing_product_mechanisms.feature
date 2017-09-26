@viewing_products
Feature: Viewing product's mechanisms
    In order to see product's specification
    As a visitor
    I want to be able to see product's mechanisms

    Background:
        Given there are default taxonomies for products

    @ui
    Scenario: Viewing a detailed page with product's mechanisms
        Given there are mechanisms "Auction" and "Area control"
        And there is product "Modern Art"
        And this product has "Auction" mechanism
        And this product also has "Area control" mechanism
        When I check this product's details
        Then I should see the mechanism name "Auction"
        And I should see the mechanism name "Area control"
