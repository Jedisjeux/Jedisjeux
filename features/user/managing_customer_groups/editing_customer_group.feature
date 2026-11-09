@managing_customer_groups
Feature: Editing a customer group
    In order to change information about a customer group
    As an Administrator
    I want to be able to edit the customer group

    Background:
        Given there is a customer group "Retailers"
        And I am a logged in administrator

    @ui
    Scenario: Renaming a customer group
        Given I want to edit the customer group "Retailers"
        When I rename it to "Wholesalers"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And I should see the customer group "Wholesalers" in the list
