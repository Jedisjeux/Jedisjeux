@managing_people
Feature: Editing a person
    In order to change information about a person
    As an Administrator
    I want to be able to edit the person

    Background:
        Given there is person with first name "Reiner" and last name "Knizia"
        And I am a logged in administrator

    @ui
    Scenario: Changing first name and last name of an existing person
        Given I want to edit "Reiner Knizia" person
        When I change its first name as "Martin"
        And I change its last name as "Wallace"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this person with name "Martin Wallace" should appear in the website
