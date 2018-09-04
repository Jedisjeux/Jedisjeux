@managing_product_reviews
Feature: Browsing product reviews
    In order to see all product reviews in the website
    As an Administrator
    I want to browse product reviews

    Background:
        Given there is a customer with email "kevin@example.com"
        And there is a customer with email "blue@example.com"
        And there is a product "Puerto Rico"
        And this product has a review titled "Awesome" and rated 5 added by customer "kevin@example.com"
        And this product has also a review titled "Bad" and rated 1 added by customer "blue@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing product reviews in website
        When I want to browse product reviews
        Then there should be 2 product reviews in the list
        And I should see the product review "Awesome" in the list
        And I should also see the product review "Bad" in the list
