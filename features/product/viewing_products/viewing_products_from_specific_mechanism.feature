@viewing_products
Feature: Viewing products from a specific mechanism
    In order to browse products that interest me most
    As a Visitor
    I want to be able to view products from a specific mechanism

    Background:
        Given there are default taxonomies for products
        And there are mechanisms "Auction" and "Area Control"
        And there is product "Modern Art"
        And this product has "Auction" mechanism
        And there is product "El Grande"
        And this product has "Area Control" mechanism

    @ui
    Scenario: Viewing products from a specific mechanism
        When I browse products from taxon "Auction"
        Then I should see the product "Modern Art"
        And I should not see the product "El Grande"
