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
        And this product has an article titled "Great book" written by customer "h.p.lovecraft@arkham.com"
        And this product has also an article titled "Scary and dark" written by customer "robert.e.howard@conan.com"
        And this product has also an article titled "Too gloomy" written by customer "jrr.tolkien@middle-earth.com"
        And I am logged in as a customer

    @ui @todo
    Scenario: Viewing all accepted product reviews on product's reviews page
        When I check this product's articles
        Then I should see 3 articles in the list

    @ui @todo
    Scenario: Viewing only accepted product reviews on product's reviews page
        Given this product has also a new article titled "Classic" written by customer "sir.terry@pratchett.com"
        When I check this product's articles
        Then I should see 3 articles in the list
        But I should not see article titled "Classic" in the list

    @ui @todo
    Scenario: Viewing no article message if there are no articles
        Given there is a product "Lux perpetua"
        When I check this product's articles
        Then I should be notified that there are no articles
