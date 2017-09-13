@managing_products
Feature: Adding a new product
    In order to extend products database
    As an Administrator or a Redactor
    I want to add a new product to the website

    @ui
    Scenario: Adding a new product with name and slug as an administrator
        Given I am logged in as an administrator
        And I want to create a new product
        And I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website

    @ui
    Scenario: Adding a new product with name and slug as a redactor
        Given I am logged in as a redactor
        And I want to create a new product
        And I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website

    @ui
    Scenario: Trying to add a new product as a staff user
        When I am logged in as a staff user
        Then I should not be able to add product
