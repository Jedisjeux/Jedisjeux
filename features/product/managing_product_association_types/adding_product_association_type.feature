@managing_product_association_types
Feature: Adding a new product association type
    In order to organise the website
    As an Administrator
    I want to add a new product association type

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new product association type
        Given I want to create a new product association type
        When I specify its code as "expansions"
        And I name it "Expansions"
        And I add it
        Then I should be notified that it has been successfully created
        And I should see the product association type "Expansions" in the list
