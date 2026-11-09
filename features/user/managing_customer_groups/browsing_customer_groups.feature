@managing_customer_groups
Feature: Browsing customer groups
    In order to see all customer groups
    As an Administrator
    I want to browse customer groups

    Background:
        Given there is a customer group "Retailers"
        And there is a customer group "Publishers"
        And I am a logged in administrator

    @ui
    Scenario: Browsing customer groups
        When I want to browse customer groups
        Then there should be 2 customer groups in the list
        And I should see the customer group "Retailers" in the list
