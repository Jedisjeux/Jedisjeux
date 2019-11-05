@managing_taxons
Feature: Adding a new mechanism
    In order to extend mechanisms database
    As an Administrator
    I want to add a new mechanism to the website

    Background:
        Given the website has locale "en_US"
        And there are default taxonomies for products

    @ui
    Scenario: Adding a new mechanism with name and slug
        Given I am a logged in administrator
        And I want to create a new mechanism
        And I specify its code as "auction"
        And I specify its name as "Auction"
        And I specify its slug as "mechanisms/auction"
        When I add it
        Then I should be notified that it has been successfully created
        And this mechanism with name "Auction" should appear in the website
