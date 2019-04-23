@viewing_product_files
Feature: Viewing product files on product's reviews page
    In order to get game add-ons
    As a Customer
    I want to read all product files on product's files page

    Background:
        Given there is a product "Necronomicon"
        And this product has a file titled "Great book" added by customer "h.p.lovecraft@arkham.com"
        And this product has also a file titled "Scary and dark" added by customer "robert.e.howard@conan.com"
        And this product has also a file titled "Too gloomy" added by customer "jrr.tolkien@middle-earth.com"
        And I am a logged in customer

    @ui
    Scenario: Viewing all product files on product's reviews page
        When I check this product's files
        Then I should see 3 product files in the list

    @ui
    Scenario: Viewing no review message if there are no reviews
        Given there is a product "Lux perpetua"
        When I check this product's files
        Then I should be notified that there are no files
