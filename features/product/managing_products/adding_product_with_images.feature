@managing_products
Feature: Adding a new product with images
    In order to extend products database
    As an Administrator
    I want to add a new product with images to the website

    Background:
        Given I am a logged in administrator

    @ui @javascript
    Scenario: Adding a new product with a single image
        Given I want to create a new product
        When I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        And I attach the "products/covers/puerto-rico.jpg" image
        And I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should have one single image
