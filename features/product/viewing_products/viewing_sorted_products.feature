@viewing_products
Feature: Sorting listed products
    In order to change the order in which products are displayed
    As a Customer
    I want to be able to sort products

    Background:
        Given there is a product "Puerto Rico", created at "first day of January 2000"
        And there is a product "Modern Art", created at "first day of January 2005"

    @ui
    Scenario: Sorting products by their dates with descending order
        When I view newest products
        Then I should see 2 products in the list
        And I should see a product with name "Puerto Rico"
        But the first product on the list should have name "Modern Art"

    @ui
    Scenario: Sorting products by their dates with ascending order
        When I view oldest products
        Then I should see 2 products in the list
        And I should see a product with name "Modern Art"
        But the first product on the list should have name "Puerto Rico"
