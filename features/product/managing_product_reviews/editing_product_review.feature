@managing_product_reviews
Feature: Editing a product
    In order to change information about a product review
    As an Administrator
    I want to be able to edit the product review

    Background:
        Given there is a customer with email "kevin@example.com"
        And there is a product "Puerto Rico"
        And this product has a review titled "Awesome" and rated 5 added by customer "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing product review
        Given I want to edit "Awesome" product review
        When I change its title as "Bad"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this product review with title "Bad" should appear in the website
