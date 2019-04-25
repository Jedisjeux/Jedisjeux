@managing_product_files
Feature: Editing a product file
    In order to change information about a product file
    As a reviewer
    I want to be able to edit the product file

    Background:
        Given there is a product "Puerto Rico"
        And this product has a file titled "French Rules" added by customer "kevin@example.com"
        And I am a logged in reviewer

    @ui
    Scenario: Changing title of an existing product file
        Given I want to edit this product file
        When I change its title to "English Rules"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this product file should be titled "English Rules"
