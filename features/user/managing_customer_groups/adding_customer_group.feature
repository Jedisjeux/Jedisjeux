@managing_customer_groups
Feature: Adding a new customer group
    In order to organise the website
    As an Administrator
    I want to add a new customer group

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new customer group
        Given I want to create a new customer group
        When I specify its code as "retailers"
        And I name it "Retailers"
        And I add it
        Then I should be notified that it has been successfully created
        And I should see the customer group "Retailers" in the list
