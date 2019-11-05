@managing_product_variants
Feature: Adding a new product variant
    In order to sell different variations of a single product
    As an Administrator
    I want to add a new product variant to the website

    Background:
        Given the website has locale "en_US"
        And there is a product "Puerto Rico standard"
        And I am a logged in administrator

    @ui
    Scenario: Adding a new product variant with name
        Given I want to create a new variant of this product
        When I name it "Puerto Rico Deluxe"
        And I add it
        Then I should be notified that it has been successfully created
        And the "Puerto Rico Deluxe" variant of the "Puerto Rico standard" product should appear in the website
