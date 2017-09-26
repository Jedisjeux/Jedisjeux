@viewing_people
Feature: Viewing a person details
    In order to see people detailed information
    As a Visitor
    I want to be able to view a single person

    @ui
    Scenario: Viewing a detailed page with person's name
        Given there is person with first name "Reiner" and last name "Knizia"
        When I check this person's details
        Then I should see the person name "Reiner Knizia"
