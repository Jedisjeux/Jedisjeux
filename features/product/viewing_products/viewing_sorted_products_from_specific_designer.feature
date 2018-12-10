@viewing_products
Feature: Sorting listed products
    In order to change the order in which products are displayed
    As a Customer
    I want to be able to sort products

    Background:
        Given there is person with name "Reiner Knizia"
        And there is person with name "Andreas Seyfarth"

    @ui
    Scenario: Sorting products from specific designer by their dates with descending order
        Given there is a product "Traumfabrik", created at "first day of January 2000"
        And this product is designed by "Reiner Knizia" person
        And there is a product "Modern Art", created at "first day of January 2005"
        And this product is designed by "Reiner Knizia" person
        And there is a product "Puerto Rico", created at "first day of January 2005"
        And this product is designed by "Andreas Seyfarth" person
        When I view newest products from person "Reiner Knizia"
        Then I should see 2 products in the list
        And I should see a product with name "Traumfabrik"
        But the first product on the list should have name "Modern Art"
        But I should not see a product with name "Puerto Rico"

    @ui
    Scenario: Sorting products from specific designer by their dates with ascending order
        Given there is a product "Traumfabrik", created at "first day of January 2000"
        And this product is designed by "Reiner Knizia" person
        And there is a product "Modern Art", created at "first day of January 2005"
        And this product is designed by "Reiner Knizia" person
        And there is a product "Puerto Rico", created at "first day of January 2005"
        And this product is designed by "Andreas Seyfarth" person
        When I view oldest products from person "Reiner Knizia"
        Then I should see 2 products in the list
        And I should see a product with name "Modern Art"
        But the first product on the list should have name "Traumfabrik"
        But I should not see a product with name "Puerto Rico"