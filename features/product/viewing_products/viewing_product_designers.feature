@viewing_products
Feature: Viewing product's designers
    In order to see product's specification
    As a visitor
    I want to be able to see product's designers

    Background:
        Given there are default taxonomies for products

    @ui
    Scenario: Viewing a detailed page with product's designers
        Given there is person with first name "Doris" and last name "Matthäus"
        And there is person with first name "Franz" and last name "Vohwinkel"
        And there is product "Modern Art"
        And this product is designed by "Doris Matthäus" person
        And this product is also designed by "Franz Vohwinkel" person
        When I check this product's details
        Then I should see the designer name "Doris Matthäus"
        And I should see the designer name "Franz Vohwinkel"
