@managing_product_boxes
Feature: Product boxes validation
    In order to avoid making mistakes when managing product boxes
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Trying to add a new product box without height
        Given I want to create a new product box
        When I do not specify its height
        And I try to add it
        Then I should be notified that the height is required
        And this product box should not be added
