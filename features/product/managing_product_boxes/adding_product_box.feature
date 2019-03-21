@managing_product_boxes
Feature: Adding product box
    In order to enrich product box image database
    As an Administrator
    I want to add a new product box image

    Background:
        Given there is a product "Puerto Rico"
        And I am a logged in administrator

    @ui
    Scenario: Adding a product box as an administrator
        Given I want to create a new product box
        When I specify its product as "Puerto Rico"
        And I attach the "products/boxes/box-puerto-rico.jpg" image
        And I specify its height as 220
        And I add it
        Then I should be notified that it has been successfully created
        And the box for product "Puerto Rico" should appear in the website
