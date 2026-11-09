@managing_customer_groups
Feature: Deleting a customer group
    In order to get rid of deprecated customer groups
    As an Administrator
    I want to be able to delete customer groups

    Background:
        Given there is a customer group "Retailers"
        And there is a customer group "Publishers"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a customer group
        Given I want to browse customer groups
        When I delete the customer group "Retailers"
        Then I should be notified that it has been successfully deleted
        And there should not be the customer group "Retailers" anymore
