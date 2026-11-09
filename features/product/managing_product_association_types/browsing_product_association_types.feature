@managing_product_association_types
Feature: Browsing product association types
    In order to see all product association types
    As an Administrator
    I want to browse product association types

    Background:
        Given there is a product association type "Expansions"
        And there is a product association type "Reprints"
        And I am a logged in administrator

    @ui
    Scenario: Browsing product association types
        When I want to browse product association types
        Then there should be 2 product association types in the list
        And I should see the product association type "Expansions" in the list
