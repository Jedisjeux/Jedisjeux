@viewing_products
Feature: Filtering listed products by duration
    In order to search products matching my needs
    As a Customer
    I want to be able to filter products by duration

    Background:
        Given there is a product "Shogun"
        And this product takes 180 minutes
        And there is a product "Puerto Rico"
        And this product takes 90 minutes
        And there is a product "Patchwork"
        And this product takes 30 minutes

    @ui
    Scenario: Filtering products which takes up to a duration
        When I view products with duration up to 90 minutes
        Then I should see 2 products in the list
        And I should see a product with name "Puerto Rico"
        And I should see a product with name "Shogun"
        But I should not see the product "Patchwork"

    @ui
    Scenario: Filtering products which takes less than a duration
        When I view products with duration less than 90 minutes
        Then I should see 2 products in the list
        And I should see a product with name "Patchwork"
        And I should see a product with name "Puerto Rico"
        But I should not see the product "Shogun"
