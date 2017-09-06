@viewing_products
Feature: Viewing products from a specific taxon
    In order to browse products that interest me most
    As a Visitor
    I want to be able to view products from a specific taxon

    Background:
        Given there is taxon with code "mechanisms"
        And this taxon has children taxon "Auction" and "Area Control"
        And there is taxon with code "themes"
        And there is taxon with code "target-audience"
        And there is product "Modern Art"
        And this product has "Auction" mechanism
        And there is product "El Grande"
        And this product has "Area Control" mechanism

    @ui
    Scenario: Viewing products from a specific taxon
        When I browse products from taxon "Auction"
        Then I should see the product "Modern Art"
        And I should not see the product "El Grande"
