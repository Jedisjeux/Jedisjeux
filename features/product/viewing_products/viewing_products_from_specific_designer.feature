@viewing_products
Feature: Viewing products from a specific designer
    In order to browse products that interest me most
    As a Visitor
    I want to be able to view products from a specific designer

    Background:
        Given there is person with first name "Martin" and last name "Wallace"
        And there is person with first name "Wolfgang" and last name "Kramer"
        And there is product "Modern Art"
        And this product is designed by "Martin Wallace" person
        And there is product "El Grande"
        And this product is designed by "Wolfgang Kramer" person

    @ui
    Scenario: Viewing products from a specific designer
        When I browse products from person "Martin Wallace"
        Then I should see the product "Modern Art"
        And I should not see the product "El Grande"
