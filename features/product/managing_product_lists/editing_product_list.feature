@managing_product_lists
Feature: Editing a product
    In order to change information about a product list
    As an Administrator
    I want to be able to edit the product list

    Background:
        Given there is a customer with email "kevin@example.com"
        And this customer has a product list "My favorite games"
        And this customer has a product list "My game library"
        And I am logged in as an administrator

    @ui
    Scenario: Renaming an existing product list
        Given I want to edit "My favorite games" product list
        When I change its name as "Awesome games"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this product list with name "Awesome games" should appear in the website
