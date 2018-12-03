@managing_product_variants
Feature: Browsing product variants
    In order to see all product variants in the website
    As an Administrator
    I want to browse product variants

    Background:
        Given there is a product "Puerto Rico standard"
        And this product has "Puerto Rico Deluxe" variant
        And I am a logged in administrator

    @ui
    Scenario: Browsing product variants in website
        When I want to browse this product variants
        Then there should be 2 product variants in the list
        And I should see the product variant "Puerto Rico Deluxe" in the list
        And I should also see the product variant "Puerto Rico standard" in the list
