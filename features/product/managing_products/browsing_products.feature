@managing_products
Feature: Browsing products
    In order to see all products in the website
    As an Administrator or a Redactor
    I want to browse products

    Background:
        Given there is a product "Puerto Rico"
        And there is a product "Modern Art"
        And there is a product "Age of Steam"

    @ui
    Scenario: Browsing products in website as an administrator
        When I am a logged in administrator
        And I want to browse products
        Then there should be 3 products in the list
        And I should see the product "Puerto Rico" in the list

    @ui
    Scenario: Browsing products in website as a redactor
        When I am a logged in redactor
        And I want to browse products
        Then there should be 3 products in the list
        And I should see the product "Puerto Rico" in the list

    @ui
    Scenario: Browsing products in website as a staff user
        When I am a logged in staff user
        And I want to browse products
        Then there should be 3 products in the list
        And I should see the product "Puerto Rico" in the list

    @ui
    Scenario: Browsing products in website as a product manager
        When I am a logged in product manager
        And I want to browse products
        Then there should be 3 products in the list
        And I should see the product "Puerto Rico" in the list

    @ui
    Scenario: Trying to browse products as a article manager
        When I am a logged in article manager
        Then I should not be able to browse products
