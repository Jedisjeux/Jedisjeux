@managing_product_boxes
Feature: Accepting a product box
    In order to validate product boxes
    As a moderator
    I need to be able to accept a product box

    Background:
        Given there is a user "kevin@example.com" identified by "password"
        And there is a product "Puerto Rico"
        And this product has also a box with "new" status added by customer "kevin@example.com"
        And I am a logged in moderator

    @ui
    Scenario: Accepting a box as a moderator
        Given I want to edit this product box
        When I accept this box
        Then I should be notified that it has been successfully accepted
        And this product box should have "accepted" status
        And there is a notification sent to "kevin@example.com"
