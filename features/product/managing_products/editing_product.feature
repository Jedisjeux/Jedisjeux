@managing_products
Feature: Editing a product
    In order to change information about a product
    As an Administrator
    I want to be able to edit the product

    Background:
        Given the website has locale "en_US"
        And there is a product "Puerto Rico"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing product
        Given I want to edit "Puerto Rico" product
        When I change its name as "Modern Art"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this product with name "Modern Art" should appear in the website
