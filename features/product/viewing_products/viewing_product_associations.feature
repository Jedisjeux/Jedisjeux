@viewing_products
Feature: Viewing product's associations
    In order to quickly navigate to other products associated with the one I'm currently viewing
    As a Visitor
    I want to see related products when viewing product details

    Background:
        Given there is a product "Ticket to Ride"
        And there are also "Ticket to Ride - Europe", "Ticket to Ride - United Kingdom", "Ticket to Ride - India" and "Ticket to Ride - Rails and Sails" products
        And there is a product association type "Expansions" with a code "expansion"
        And there is also a product association type "Collection" with a code "collection"
        And the product "Ticket to Ride" has an association "Expansions" with products "Ticket to Ride - United Kingdom" and "Ticket to Ride - India"
        And the product "Ticket to Ride" has also an association "Collection" with products "Ticket to Ride - Europe" and "Ticket to Ride - Europe"

    @ui
    Scenario: Viewing a detailed page with product's associations
        When I view product "Ticket to Ride"
        Then I should see the product association "Expansions" with products "Ticket to Ride - United Kingdom" and "Ticket to Ride - India"
        And I should also see the product association "Collection" with products "Ticket to Ride - Europe" and "Ticket to Ride - Europe"
