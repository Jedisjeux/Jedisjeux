@viewing_products
Feature: Viewing products
    In order to see products
    As a Visitor
    I want to be able to browse products

    Background:
        Given there are default taxonomies for products

    @ui
    Scenario: Viewing products
        Given there is product "Puerto Rico"
        And there is product "Modern Art"
        When I want to browse products
        Then I should see the product "Puerto Rico"
        And I should see the product "Modern Art"
