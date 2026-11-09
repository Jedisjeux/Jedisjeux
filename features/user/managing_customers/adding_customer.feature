@managing_customers
Feature: Adding a new customer
    In order to register people who cannot do it themselves
    As an Administrator
    I want to add a new customer

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new customer with an account
        Given I want to create a new customer
        When I specify their email as "f.baggins@example.com"
        And I choose to create their account with password "Sting2024!"
        And I add them
        Then I should be notified that it has been successfully created
        And I should see the customer "f.baggins@example.com" in the list
