@managing_product_variants
Feature: Editing a product variant
    In order to change information about a product variant
    As an Administrator
    I want to be able to edit the product variant

    Background:
        Given there is a product "Puerto Rico standard"
        And this product has "Puerto Rico Deluxe" variant
        And I am a logged in administrator

    @ui
    Scenario: Renaming a product variant
        Given I want to edit the "Puerto Rico Deluxe" variant of this product
        When I rename it to "Puerto Rico Collector"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And I should see the product variant "Puerto Rico Collector" in the list
        But I should not see the product variant "Puerto Rico Deluxe" in the list
