@managing_taxons
Feature: Browsing taxonomies
    In order to see all taxonomies in the website
    As an Administrator
    I want to browse taxonomies

    Background:
        Given there are default taxonomies for products
        And I am a logged in administrator

    @ui
    Scenario: Browsing taxonomies
        Given I want to browse taxons
        Then I should see 3 taxons on the list
