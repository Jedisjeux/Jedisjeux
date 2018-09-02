@viewing_products
Feature: Viewing product's designers
    In order to see product's specification
    As a visitor
    I want to be able to see product's designers

    Background:
        Given there are default taxonomies for products

    @ui
    Scenario: Viewing a detailed page with product's designers
        Given there is person with first name "Reiner" and last name "Knizia"
        And there is person with first name "Wolfgang" and last name "Kramer"
        And there is product "Modern Art"
        And this product is designed by "Reiner Knizia" person
        And this product is also designed by "Wolfgang Kramer" person
        When I check this product's details
        Then I should see the designer name "Reiner Knizia"
        And I should see the designer name "Wolfgang Kramer"
