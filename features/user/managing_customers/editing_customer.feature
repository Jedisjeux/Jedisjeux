@managing_customers
Feature: Editing a customer
    In order to change information about a customer
    As an Administrator
    I want to be able to edit the customer

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Changing email of an existing customer
        Given there is a customer with email "f.baggins@example.com"
        And I want to edit this customer
        When I change their email to "j.snow@example.com"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this customer with email "j.snow@example.com" should appear in the store
