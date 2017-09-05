@viewing_products
Feature: Viewing products
    In order to see products
    As a Visitor
    I want to be able to browse products list

    Background:
        Given there is taxon with code "mechanisms"
        And there is taxon with code "themes"
        And there is taxon with code "target-audience"

    @ui
    Scenario: Viewing products list
        Given there is product "Puerto Rico"
        And there is product "Modern Art"
        When I want to browse products
        Then I should see the product "Puerto Rico"
        And I should see the product "Modern Art"