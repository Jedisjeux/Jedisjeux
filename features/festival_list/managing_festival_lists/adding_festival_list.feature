@managing_festival_lists
Feature: Adding a new festival list
    In order to extend festival lists database
    As an Administrator
    I want to add a new festival list to the website

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new festival list with name
        Given I want to create a new festival list
        When I specify its name as "Essen 2017"
        When I add it
        Then I should be notified that it has been successfully created
        And the festival list "Essen 2017" should appear in the website

    @ui
    Scenario: Adding a new festival list with full details
        Given I want to create a new festival list
        When I specify its name as "Essen 2017"
        And I specify its description as "This is an awesome description of Essen 2017."
        And I specify its start at as "12/09/2017"
        And I specify its end at as "31/10/2017"
        When I add it
        Then I should be notified that it has been successfully created
        And the festival list "Essen 2017" should appear in the website