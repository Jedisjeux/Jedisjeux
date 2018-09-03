@viewing_products
Feature: Viewing a product details
    In order to see products detailed information
    As a Visitor
    I want to be able to view a single product

    @ui
    Scenario: Viewing a detailed page with product's name
        Given there is a product "Puerto Rico"
        When I check this product's details
        Then I should see the product name "Puerto Rico"
