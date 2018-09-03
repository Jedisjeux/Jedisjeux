@adding_product_reviews
Feature: Adding product review as a customer
    In order to share my opinion about a product
    As a Customer
    I want to be able to add my product review

    Background:
        Given I am logged in as a customer
        And there is a product "Puerto Rico"
        And I wrote a review on this product

    @ui @javascript @todo
    Scenario: Adding my product review
        Given I want to review this product
        When I leave a comment "This is an awesome product", titled "Awesome product!"
        And I rate it with 8 points
        And I add it
        Then I should be notified that it has been successfully created
