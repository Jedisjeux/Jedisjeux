@viewing_products
Feature: Allow viewing unpublished product details
    In order to view unpublished products detailed information
    As a product manager
    I am able to view a product which is not published

    Background:
        Given I am logged in as a product manager

    @ui
    Scenario: Viewing details of a product with new status
        Given there is a product "Puerto Rico" with "new" status
        When I should be able to see this product's details

    @ui
    Scenario: Viewing details of a product with pending translation status
        When there is a product "Puerto Rico" with "pending translation" status
        Then I should be able to see this product's details

    @ui
    Scenario: Viewing details of a product with pending review status
        When there is a product "Puerto Rico" with "pending review" status
        Then I should be able to see this product's details

    @ui
    Scenario: Viewing details of a product with pending publication status
        When there is a product "Puerto Rico" with "pending publication" status
        Then I should be able to see this product's details
