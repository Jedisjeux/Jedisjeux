@managing_taxons
Feature: Deleting a mechanism
    In order to get rid of deprecated mechanisms
    As an Administrator
    I want to be able to delete mechanisms

    Background:
        Given there are default taxonomies for products
        And there are mechanisms "Auction" and "Area control"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a mechanism
        Given I want to browse mechanisms
        When I delete mechanism with name "Auction"
        Then I should be notified that it has been successfully deleted
        And there should not be "Auction" mechanism anymore
