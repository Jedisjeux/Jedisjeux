@managing_people
Feature: Deleting a person
    In order to get rid of deprecated people
    As an Administrator
    I want to be able to delete people

    Background:
        Given there is person with first name "Reiner" and last name "Knizia"
        And there is person with first name "Martin" and last name "Wallace"
        And I am logged in as an administrator

    @ui
    Scenario: Deleting a person
        Given I want to browse people
        When I delete person with name "Reiner Knizia"
        Then I should be notified that it has been successfully deleted
        And there should not be "Reiner Knizia" person anymore
