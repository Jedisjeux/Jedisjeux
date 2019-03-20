@adding_product_box_images
Feature: Adding product box image as a customer
    In order to have a beautiful virtual game library
    As a Customer
    I want to be able to add a product box image

    Background:
        Given I am a logged in customer
        And there is a product "Puerto Rico"

    @ui
    Scenario: Adding a product box image
        Given I want to add a product box image to this product
        When I attach the "products/boxes/box-puerto-rico.jpg" image
        And I add it
        Then I should be notified that my image is waiting for the acceptation
