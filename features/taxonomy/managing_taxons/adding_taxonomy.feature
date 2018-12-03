@managing_taxons
Feature: Adding a new taxonomy
    In order to extend taxonomies database
    As an Administrator
    I want to add a new taxonomy to the website

    @ui
    Scenario: Adding a new taxonomy with name and slug
        Given I am a logged in administrator
        And I want to create a new taxonomy
        And I specify its code as "categories"
        And I specify its name as "Categories"
        And I specify its slug as "categories"
        When I add it
        Then I should be notified that it has been successfully created
        And this taxonomy with name "Categories" should appear in the website
