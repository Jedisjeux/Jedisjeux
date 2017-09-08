@managing_staff_lists
Feature: Adding a new staff list
    In order to extend staff lists database
    As an Administrator
    I want to add a new staff list to the website

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Adding a new staff list with name
        Given I want to create a new staff list
        When I specify its name as "Essen 2017"
        When I add it
        Then I should be notified that it has been successfully created
        And the staff list "Essen 2017" should appear in the website

    @ui
    Scenario: Adding a new staff list with full details
        Given I want to create a new staff list
        When I specify its name as "Essen 2017"
        And I specify its description as "This is an awesome description of Essen 2017."
        And I specify its start at as "12/09/2017"
        And I specify its end at as "31/10/2017"
        When I add it
        Then I should be notified that it has been successfully created
        And the staff list "Essen 2017" should appear in the website