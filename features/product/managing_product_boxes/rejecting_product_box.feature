@managing_product_boxes
Feature: Rejecting a product box
    In order to validate product boxes
    As a moderator
    I need to be able to reject a product box

    Background:
        Given there is a user "kevin@example.com" identified by "password"
        And there is a product "Puerto Rico"
        And this product has also a box with "new" status added by customer "kevin@example.com"
        And I am a logged in moderator

    @ui
    Scenario: Rejecting a box as a moderator
        Given I want to edit this product box
        When I reject this box
        Then I should be notified that it has been successfully rejected
        And this product box should have "rejected" status
        And there is a notification sent to "kevin@example.com"
