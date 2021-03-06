@editing_product_reviews
Feature: Editing product review as a customer
    In order to edit my product review information
    As a Customer
    I want to be able to edit product review

    Background:
        Given I am a logged in customer
        And there is a product "Puerto Rico"
        And I wrote a review on this product

    @ui
    Scenario: Changing my product review title
        Given I want to edit this product review
        When I change its title as "Awesome product!"
        And I save my changes
        Then I should be notified that it has been successfully edited

    @ui
    Scenario: Changing my product review comment
        Given I want to edit this product review
        When I change its comment as "This is an awesome product!"
        And I save my changes
        Then I should be notified that it has been successfully edited

    @ui @javascript
    Scenario: Changing my product review rate
        Given I want to edit this product review
        When I change its rate with 5 points
        And I save my changes
        Then I should be notified that it has been successfully edited
