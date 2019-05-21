@managing_product_files
Feature: Deleting a product file
    In order to get rid of deprecated game plays
    As a moderator
    I want to be able to delete game plays

    Background:
        Given there is a product "Puerto Rico"
        And this product has a file titled "French Rules" added by customer "kevin@example.com"
        And I am a logged in moderator

    @ui
    Scenario: Deleting a product file
        Given I want to browse product files
        When I delete product file "French Rules"
        Then I should be notified that it has been successfully deleted
        And there should not be product file "French Rules" anymore
