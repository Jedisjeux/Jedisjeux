@managing_taxons
Feature: Browsing mechanisms
    In order to see all mechanisms in the website
    As an Administrator
    I want to browse mechanisms

    Background:
        Given there are default taxonomies for products
        And there are mechanisms "Auction" and "Area control"
        And I am a logged in administrator

    @ui
    Scenario: Browsing mechanisms
        Given I want to browse mechanisms
        Then I should see 2 taxons on the list
        And I should see the taxon named "Auction" in the list
        And I should also see the taxon named "Area control" in the list
