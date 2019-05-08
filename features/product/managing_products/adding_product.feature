@managing_products
Feature: Adding a new product
    In order to extend products database
    As an Administrator or a Redactor
    I want to add a new product to the website
    
    Background: 
        Given the website has locale "en_US"

    @ui
    Scenario: Adding a new product with name and slug as an administrator
        Given I am a logged in administrator
        And I want to create a new product
        And I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website

    @ui
    Scenario: Adding a new product with name and slug as a redactor
        Given I am a logged in redactor
        And I want to create a new product
        And I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website

    @ui
    Scenario: Adding a new product from BoardGameGeek as an administrator
        Given I am a logged in administrator
        And I want to create a new product via bgg url "https://boardgamegeek.com/boardgame/3076/puerto-rico"
        And I specify his slug as "puerto-rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website

    @ui
    Scenario: Adding a new product from BoardGameGeek as a redactor
        Given I am a logged in redactor
        And I want to create a new product via bgg url "https://boardgamegeek.com/boardgame/3076/puerto-rico"
        And I specify his slug as "puerto-rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website
