@viewing_products
Feature: Filtering listed products by player count
    In order to search products matching my needs
    As a Customer
    I want to be able to filter products player count

    Background:
        Given there is a product "Les Princes de Florence"
        And this product can be played from 3 to 5 players
        And there is a product "Puerto Rico"
        And this product can be played from 2 to 5 players
        And there is a product "Patchwork"
        And this product can only be played with 2 players

    @ui
    Scenario: Filtering products which can be played with a number of players
        When I view products which can be played with 3 players
        Then I should see 2 products in the list
        And I should see a product with name "Les Princes de Florence"
        And I should see a product with name "Puerto Rico"
        But I should not see the product "Patchwork"
