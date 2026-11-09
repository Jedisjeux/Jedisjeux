@managing_product_association_types
Feature: Editing a product association type
    In order to change information about a product association type
    As an Administrator
    I want to be able to edit the product association type

    Background:
        Given there is a product association type "Expansions"
        And I am a logged in administrator

    @ui
    Scenario: Renaming a product association type
        Given I want to edit the product association type "Expansions"
        When I rename it to "Accessories"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And I should see the product association type "Accessories" in the list
