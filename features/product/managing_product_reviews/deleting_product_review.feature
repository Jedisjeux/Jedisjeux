@managing_product_reviews
Feature: Deleting product reviews
    In order to remove test, obsolete or incorrect product reviews
    As an Administrator
    I want to be able to delete a product review

    Background:
        Given there is a customer with email "kevin@example.com"
        And there is a product "Puerto Rico"
        And this product has a review titled "Awesome" and rated 5 added by customer "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a product review
        When I delete the "Awesome" product review
        Then I should be notified that it has been successfully deleted
        And there should not be "Awesome" product review anymore
