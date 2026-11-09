@managing_product_association_types
Feature: Deleting a product association type
    In order to get rid of deprecated product association types
    As an Administrator
    I want to be able to delete product association types

    Background:
        Given there is a product association type "Expansions"
        And there is a product association type "Reprints"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a product association type
        Given I want to browse product association types
        When I delete the product association type "Expansions"
        Then I should be notified that it has been successfully deleted
        And there should not be the product association type "Expansions" anymore
