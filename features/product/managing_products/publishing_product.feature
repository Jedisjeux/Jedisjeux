@managing_products
Feature: Publishing product
    In order to publish products
    As a publisher
    I need to be able to publish a product

    Background:
        Given the website has locale "en_US"
        And there is a product "Puerto Rico" with "pending_publication" status
        And I am a logged in publisher

    @ui
    Scenario: Publishing a product as a publisher
        Given I want to edit "Puerto Rico" product
        When I publish it
        Then I should be notified that it has been successfully edited
        And this product with name "Puerto Rico" should have "published" status
