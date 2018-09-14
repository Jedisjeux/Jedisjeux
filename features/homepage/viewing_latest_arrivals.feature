@viewing_products_on_homepage
Feature: Viewing a latest arrivals list
    In order to be up-to-date with the arrivals
    As a Visitor
    I want to be able to view a latest arrivals list

    Background:
        Given there is a product "Shogun", released "1 days ago"
        And there is also a product "Patchwork", released "2 days ago"
        And there is also a product "Die Fürsten Von Florenz", released "3 days ago"
        And there is also a product "Carolus Magnus", released "4 days ago"
        And there is also a product "Puerto Rico", released "5 days ago"
        And there is also a product "Monopoly", released "80 years ago"

    @ui
    Scenario: Viewing latest arrivals
        When I check latest articles
        Then I should see 5 products in the latest arrivals list
        And I should see the product "Shogun" in the latest arrivals list
        And I should see the product "Patchwork" in the latest arrivals list
        And I should see the product "Die Fürsten Von Florenz" in the latest arrivals list
        And I should see the product "Carolus Magnus" in the latest arrivals list
        And I should see the product "Puerto Rico" in the latest arrivals list
        But I should not see the product "Monopoly" in the latest arrivals list
