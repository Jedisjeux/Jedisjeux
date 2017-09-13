@managing_dealers
Feature: Adding a new dealer
    In order to extend dealers database
    As an Administrator
    I want to add a new dealer to the website

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Adding a new dealer with code and name
        Given I want to create a new dealer
        When I specify his code as "philibert"
        And I specify his name as "Philibert"
        When I add it
        Then I should be notified that it has been successfully created
        And the dealer "Philibert" should appear in the website
