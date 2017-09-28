@viewing_products
Feature: Viewing products from a specific theme
    In order to browse products that interest me most
    As a Visitor
    I want to be able to view products from a specific theme

    Background:
        Given there are default taxonomies for products
        And there are themes "Renaissance" and "Medieval"
        And there is product "El Grande"
        And this product has "Renaissance" theme
        And there is product "Caylus"
        And this product has "Medieval" theme

    @ui
    Scenario: Viewing products from a specific theme
        When I browse products from taxon "Renaissance"
        Then I should see the product "El Grande"
        And I should not see the product "Caylus"
