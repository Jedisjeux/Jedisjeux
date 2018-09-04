@viewing_product_articles
Feature: Viewing product reviews on product's details page
    In order to know other customer's opinion about product
    As a Customer
    I want to read product reviews on product's details page

    Background:
        Given there are default taxonomies for articles
        And there is a product "Necronomicon"
        And this product has an article titled "Great book" written by customer "h.p.lovecraft@arkham.com", published 3 days ago
        And this product has also an article titled "Scary and dark" written by customer "robert.e.howard@conan.com"
        And this product has also an article titled "Too gloomy" written by customer "jrr.tolkien@middle-earth.com"
        And this product has also an article titled "Classic" written by customer "sir.terry@pratchett.com"
        And I am a logged in customer

    @ui
    Scenario: Viewing last 3 articles on product's details page
        When I check this product's details
        Then I should see 3 articles
        And I should see articles titled "Classic", "Too gloomy" and "Scary and dark"
        But I should not see review titled "Good book"
