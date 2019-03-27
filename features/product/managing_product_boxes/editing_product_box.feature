@managing_product_boxes
Feature: Editing a product box
    In order to change information about a product box
    As a reviewer
    I want to be able to edit the product box

    Background:
        Given there is a product "Puerto Rico"
        And this product has a box
        And I am a logged in reviewer

    @ui
    Scenario: Changing height of an existing product box
        Given I want to edit this product box
        When I change its height to 220
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this product box should be 220 high
