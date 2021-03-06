@adding_product_boxes
Feature: Adding product box as a customer
    In order to have a beautiful virtual game library
    As a Customer
    I want to be able to add a product box image

    Background:
        Given there is a product "Puerto Rico"
        And there is a moderator "moderator@example.com"
        And I am a logged in customer

    @ui
    Scenario: Adding a product box as a customer
        Given I want to add a product box image to this product
        When I attach the "products/boxes/box-puerto-rico.jpg" image
        And I specify its height as 220
        And I add it
        Then I should be notified that my image is waiting for the acceptation
        And there is a notification sent to "moderator@example.com"
