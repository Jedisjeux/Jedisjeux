@viewing_products
Feature: Viewing the releases
    In order to know which games come out
    As a Visitor
    I want to see the games released this month, the upcoming ones and the past ones

    Background:
        Given there is a product "Puerto Rico", released "first day of this month"
        And there is also a product "Modern Art", released "first day of next month"
        And there is also a product "Ticket to Ride", released "-3 months"

    @ui
    Scenario: Viewing the releases of the month, the upcoming ones and the past ones
        When I check the releases
        Then I should see the product "Puerto Rico" in the releases of the month
        And I should see the product "Modern Art" in the upcoming releases
        And I should see the product "Ticket to Ride" in the past releases
        But I should not see the product "Modern Art" in the releases of the month
        And I should not see the product "Ticket to Ride" in the upcoming releases
