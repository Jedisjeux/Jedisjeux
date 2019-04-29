@managing_products
Feature: Asking for review
    In order to publish my products
    As a redactor
    I need to be able to ask for a review

    Background:
        Given there is a reviewer "obiwan@example.com"
        And there is a reviewer "luke@example.com"
        And I am a logged in redactor
        And there is a product "Puerto Rico" with "new" status

    @ui
    Scenario: Asking for a product review as a redactor
        Given I want to edit "Puerto Rico" product
        When I ask for a review
        Then I should be notified that it has been successfully edited
        And this product with name "Puerto Rico" should have "pending review" status
        And customer "obiwan@example.com" should have received a notification for product "Puerto Rico"
        And customer "luke@example.com" should also have received a notification for product "Puerto Rico"
