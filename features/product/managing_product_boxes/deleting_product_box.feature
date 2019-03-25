@managing_product_boxes
Feature: Deleting a product box
    In order to get rid of deprecated game plays
    As a reviewer
    I want to be able to delete game plays

    Background:
        Given there is a product "Puerto Rico"
        And this product has a box
        And I am a logged in reviewer

    @ui
    Scenario: Deleting a product box
        Given I want to browse product boxes
        When I delete product box of "Puerto Rico"
        Then I should be notified that it has been successfully deleted
        And there should not be product box of "Puerto Rico" anymore
