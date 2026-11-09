@managing_product_articles
Feature: Writing an article about a product
    In order to present a product
    As an Administrator
    I want to write articles about a product

    Background:
        Given there is a product "Puerto Rico"
        And I am a logged in administrator

    @ui
    Scenario: Writing an article about a product
        Given I want to write an article about this product
        When I specify its title as "Puerto Rico is back"
        And I add this article
        Then I should be notified that it has been successfully created
        And the article "Puerto Rico is back" should be about this product

    @ui
    Scenario: Writing a review of a product
        Given I want to write a review of this product
        When I add this review
        Then I should be notified that it has been successfully created
        And the article "Critique de Puerto Rico" should be about this product
