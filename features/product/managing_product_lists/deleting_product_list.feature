@managing_product_lists
Feature: Deleting product lists
    In order to remove test, obsolete or incorrect product lists
    As an Administrator
    I want to be able to delete a product list

    Background:
        Given there is a customer with email "kevin@example.com"
        And this customer has a product list "My favorite games"
        And this customer has a product list "My game library"
        And I am logged in as an administrator

    @ui @todo
    Scenario: Deleting a product list
        When I delete the "My favorite games" product list
        Then I should be notified that it has been successfully deleted
        And there should not be "My favorite games" product list anymore
