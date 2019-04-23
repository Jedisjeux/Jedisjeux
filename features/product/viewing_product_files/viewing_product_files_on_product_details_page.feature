@viewing_product_files
Feature: Viewing product files on product's details page
    In order to get game add-ons
    As a Customer
    I want to read product files on product's details page

    Background:
        Given there is a product "Necronomicon"
        And this product has also a file titled "Scary and dark" added by customer "robert.e.howard@conan.com"
        And this product has also a file titled "Too gloomy" added by customer "jrr.tolkien@middle-earth.com"
        And this product has also a file titled "Classic" added by customer "sir.terry@pratchett.com"
        And I am a logged in customer

    @ui
    Scenario: Viewing last 3 files on product's details page
        When I check this product's details
        Then I should see 3 files
        And I should see files titled "Classic", "Too gloomy" and "Scary and dark"
