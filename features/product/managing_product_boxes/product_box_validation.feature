@managing_product_boxes
Feature: Product boxes validation
    In order to avoid making mistakes when managing product boxes
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am a logged in administrator
        And there is a product "Puerto Rico"
        And this product has a box 220 high

    @ui
    Scenario: Trying to remove product box height
        Given I want to edit this product box
        When I remove its height
        And I try to save my changes
        Then I should be notified that the height is required
        And this product box should still be 220 high

    @ui
    Scenario: Trying to update a product box image with wrong type
        Given I want to edit this product box
        When I attach the "philibert.csv" image
        And I try to save my changes
        Then I should be notified that the file is not a valid image
