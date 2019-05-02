@viewing_products
Feature: Viewing product's variants names
    In order to differentiate product's variants by names
    As a Customer
    I want to be aware of product's variants names

    Background:
        Given there is a product "Puerto Rico standard"
        And this product has "Puerto Rico Deluxe" variant

    @ui
    Scenario: Seeing variant's name
        When I check this product's details
        Then I should see the variant name "Puerto Rico Deluxe"
