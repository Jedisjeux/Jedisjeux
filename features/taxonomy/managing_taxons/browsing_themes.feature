@managing_taxons
Feature: Browsing themes
    In order to see all themes in the website
    As an Administrator
    I want to browse themes

    Background:
        Given there are default taxonomies for products
        And there are themes "Renaissance" and "History"
        And I am a logged in administrator

    @ui
    Scenario: Browsing themes
        Given I want to browse themes
        Then I should see 2 taxons on the list
        And I should see the taxon named "Renaissance" in the list
        And I should also see the taxon named "History" in the list
