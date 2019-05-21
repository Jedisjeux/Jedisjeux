@managing_product_files
Feature: Accepting a product file
    In order to validate product files
    As a moderator
    I need to be able to accept a product file

    Background:
        Given there is a user "kevin@example.com" identified by "password"
        And there is a product "Puerto Rico"
        And this product has a file titled "French Rules" with "new" status added by customer "kevin@example.com"
        And I am a logged in moderator

    @ui
    Scenario: Accepting a file as a moderator
        Given I want to edit this product file
        When I accept this file
        Then I should be notified that it has been successfully accepted
        And this product file should have "accepted" status
        And there is a notification sent to "kevin@example.com"
