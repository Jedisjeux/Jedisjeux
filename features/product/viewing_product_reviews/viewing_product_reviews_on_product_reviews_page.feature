@viewing_product_reviews
Feature: Viewing product reviews on product's reviews page
    In order to know other customer's opinion about product
    As a Customer
    I want to read all product reviews on product's reviews page

    Background:
        Given there is a product "Necronomicon"
        And there is a customer with email "h.p.lovecraft@arkham.com"
        And there is a customer with email "robert.e.howard@conan.com"
        And there is a customer with email "jrr.tolkien@middle-earth.com"
        And this product has a review titled "Great book" and rated 5 added by customer "h.p.lovecraft@arkham.com"
        And this product has also a review titled "Scary and dark" and rated 4 added by customer "robert.e.howard@conan.com"
        And this product has also a review titled "Too gloomy" and rated 3 added by customer "jrr.tolkien@middle-earth.com"
        And I am logged in as a customer

    @ui
    Scenario: Viewing all accepted product reviews on product's reviews page
        When I check this product's reviews
        Then I should see 3 product reviews in the list

    @ui @todo
    Scenario: Viewing only accepted product reviews on product's reviews page
        Given this product has also a new review titled "Classic" and rated 5 added by customer "sir.terry@pratchett.com"
        When I check this product's reviews
        Then I should see 3 product reviews in the list
        But I should not see review titled "Classic" in the list

    @ui
    Scenario: Viewing no review message if there are no reviews
        Given there is a product "Lux perpetua"
        When I check this product's reviews
        Then I should be notified that there are no reviews
