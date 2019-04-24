@managing_product_files
Feature: Product files validation
    In order to avoid making mistakes when managing product files
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am a logged in administrator
        And there is a product "Puerto Rico"
        And this product has a file titled "French Rules" added by customer "kevin@example.com"

    @ui
    Scenario: Trying to remove product file title
        Given I want to edit this product file
        When I remove its title
        And I try to save my changes
        Then I should be notified that the title is required
        And this product file should still be titled "French Rules"

    @ui
    Scenario: Trying to update a product file image with wrong type
        Given I want to edit this product file
        When I attach the "philibert.csv" file
        And I try to save my changes
        Then I should be notified that the file is not a valid file
