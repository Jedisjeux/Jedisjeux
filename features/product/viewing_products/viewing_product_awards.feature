@viewing_products
Feature: Viewing product's awards
    In order to see product's awards
    As a visitor
    I want to be able to see product's awards

    Background:
        Given there are default taxonomies for products
        And there is a game award "Spiel des Jahres"
        And there is also a game award "As d'or"
        And there is a product "Modern Art"
        And game award "Spiel des Jahres" has been attributed to this product in "2018"
        And game award "As d'or" has been attributed to this product in "2017"

    @ui
    Scenario: Viewing a detailed page with product's awards
        When I check this product's details
        Then I should see the award name "Spiel des Jahres 2018"
        And I should see the award name "As d'or 2017"
