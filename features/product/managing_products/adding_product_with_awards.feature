@managing_products
Feature: Adding a new product with awards
    In order to extend game awards database
    As an Administrator
    I want to add a new product with awards to the website

    Background:
        Given the website has locale "en_US"
        And I am a logged in administrator
        And there is a game award "Spiel des Jahres"

    @ui @javascript
    Scenario: Adding a new product with awards
        And I want to create a new product
        And I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        And I add a new award "Spiel des Jahres" 2018
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website
