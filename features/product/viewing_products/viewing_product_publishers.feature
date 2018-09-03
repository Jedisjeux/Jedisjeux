@viewing_products
Feature: Viewing product's publishers
    In order to see product's specification
    As a visitor
    I want to be able to see product's publishers

    Background:
        Given there are default taxonomies for products

    @ui
    Scenario: Viewing a detailed page with product's publishers
        Given there is person with first name "Reiner" and last name "Knizia"
        And there is person with first name "Wolfgang" and last name "Kramer"
        And there is a product "Modern Art"
        And this product is published by "Reiner Knizia" person
        And this product is also published by "Wolfgang Kramer" person
        When I check this product's details
        Then I should see the publisher name "Reiner Knizia"
        And I should see the publisher name "Wolfgang Kramer"
