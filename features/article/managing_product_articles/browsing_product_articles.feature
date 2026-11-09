@managing_product_articles
Feature: Browsing the articles about a product
    In order to see what has been written about a product
    As an Administrator
    I want to browse the articles about a product

    Background:
        Given there are default taxonomies for articles
        And there is a product "Modern Art"
        And this product has an article titled "Modern Art is back" written by customer "kevin@example.com"
        And there is a product "Puerto Rico"
        And this product has an article titled "Puerto Rico review" written by customer "kevin@example.com"
        And this product has also an article titled "Puerto Rico interview" written by customer "kevin@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing only the articles about a product
        When I want to browse the articles of this product
        Then there should be 2 articles about this product in the list
