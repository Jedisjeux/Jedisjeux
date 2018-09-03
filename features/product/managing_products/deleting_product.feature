@managing_products
Feature: Deleting a product
    In order to get rid of deprecated products
    As an Administrator
    I want to be able to delete products

    Background:
        Given there is a product "Puerto Rico"
        And there is a product "Modern Art"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting a product
        Given I want to browse products
        When I delete product with name "Puerto Rico"
        Then I should be notified that it has been successfully deleted
        And there should not be "Puerto Rico" product anymore
