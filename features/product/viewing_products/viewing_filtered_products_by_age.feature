@viewing_products
Feature: Filtering listed products by age
    In order to search products matching my needs
    As a Customer
    I want to be able to filter products by age

    Background:
        Given there is a product "Shogun"
        And this product can be played from 14 years
        And there is a product "Puerto Rico"
        And this product can be played from 12 years
        And there is a product "Pique Plume"
        And this product can be played from 5 years

    @ui
    Scenario: Filtering products which can be played up with an age
        When I view products with age up to 12 years
        Then I should see 2 products in the list
        And I should see a product with name "Puerto Rico"
        And I should see a product with name "Pique Plume"
        But I should not see the product "Shogun"
