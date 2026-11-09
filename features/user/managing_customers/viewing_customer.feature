@managing_customers
Feature: Viewing the details of a customer
    In order to know more about a customer
    As an Administrator
    I want to view the details of a customer

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Viewing the details of a customer
        Given there is a customer with email "f.baggins@example.com"
        When I view the details of this customer
        Then I should see their email "f.baggins@example.com"
