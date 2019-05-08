@managing_taxons
Feature: Editing a mechanism
    In order to change information about a mechanism
    As an Administrator
    I want to be able to edit the mechanism

    Background:
        Given the website has locale "en_US"
        And there are default taxonomies for products
        And there is a mechanism "Auction"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing mechanism
        Given I want to edit "Auction" mechanism
        When I change its name as "Area control"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this mechanism with name "Area control" should appear in the website
