@viewing_products
Feature: Viewing a product details
    In order to see products box content
    As a Visitor
    I want to view box content on product details page

    Background:
        Given the website has locale "en_US"

    @ui
    Scenario: Viewing box content
        Given there is a product "Puerto Rico"
        And this product has "a Game Board" in its box
        And this product also has "42 Tiles" in its box
        And this product also has "33 Meeples" in its box
        When I check this product's details
        Then I should see "a Game Board", "42 Tiles", "33 Meeples" as box content
