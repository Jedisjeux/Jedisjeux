@viewing_products
Feature: Sorting listed products
    In order to change the order in which products are displayed
    As a Customer
    I want to be able to sort products

    @ui
    Scenario: Sorting products by their dates with descending order
        Given there is a product "Puerto Rico", created at "first day of January 2000"
        And there is a product "Modern Art", created at "first day of January 2005"
        When I view newest products
        Then I should see 2 products in the list
        And I should see a product with name "Puerto Rico"
        But the first product on the list should have name "Modern Art"

    @ui
    Scenario: Sorting products by their dates with ascending order
        Given there is a product "Puerto Rico", created at "first day of January 2000"
        And there is a product "Modern Art", created at "first day of January 2005"
        When I view oldest products
        Then I should see 2 products in the list
        And I should see a product with name "Modern Art"
        But the first product on the list should have name "Puerto Rico"

    @ui
    Scenario: Sorting products by their release dates with descending order
        Given there is a product "Puerto Rico", released "10 years ago"
        And there is a product "Modern Art", released "5 years ago"
        When I view newest release products
        Then I should see 2 products in the list
        And I should see a product with name "Puerto Rico"
        But the first product on the list should have name "Modern Art"

    @ui
    Scenario: Sorting products by their release dates with ascending order
        Given there is a product "Puerto Rico", released "10 years ago"
        And there is a product "Modern Art", released "5 years ago"
        When I view oldest release products
        Then I should see 2 products in the list
        And I should see a product with name "Modern Art"
        But the first product on the list should have name "Puerto Rico"

    @ui
    Scenario: Sorting products by their reviews with descending order
        Given there is a product "Puerto Rico"
        And this product has a review titled "This game is awesome!" and rated 8 added by customer "j.tolkien@example.com", created 5 days ago
        And there is a product "Modern Art"
        When I view top commented products
        Then I should see 2 products in the list
        And I should see a product with name "Modern Art"
        But the first product on the list should have name "Puerto Rico"
