@managing_customers
Feature: Browsing customers
    In order to see all customers in the website
    As an Administrator
    I want to browse customers

    Background:
        Given there is a customer with email "f.baggins@example.com"
        And there is also a customer with email "mr.banana@example.com"
        And there is also a customer with email "l.skywalker@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing customers in website
        When I want to browse customers
        Then I should see 4 customers in the list
        And I should see the customer "mr.banana@example.com" in the list
