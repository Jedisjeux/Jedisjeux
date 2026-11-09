@managing_product_variants
Feature: Deleting a product variant
    In order to get rid of deprecated product variants
    As an Administrator
    I want to be able to delete product variants

    Background:
        Given there is a product "Puerto Rico standard"
        And this product has "Puerto Rico Deluxe" variant
        And I am a logged in administrator

    @ui
    Scenario: Deleting a product variant
        Given I want to browse this product variants
        When I delete the product variant "Puerto Rico Deluxe"
        Then I should be notified that it has been successfully deleted
        And there should be 1 product variants in the list
        And I should not see the product variant "Puerto Rico Deluxe" in the list
