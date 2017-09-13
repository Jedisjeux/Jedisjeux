@managing_people
Feature: People validation
    In order to avoid making mistakes when managing people
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Trying to add a new person without last name
        Given I want to create a new person
        When I do not specify its last name
        And I try to add it
        Then I should be notified that the last name is required
        And this person should not be added
