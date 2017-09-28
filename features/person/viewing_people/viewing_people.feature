@viewing_people
Feature: Viewing people
    In order to see people
    As a Visitor
    I want to be able to browse people

    Background:
        Given there are default taxonomies for people

    @ui
    Scenario: Viewing people
        Given there is person with first name "Reiner" and last name "Knizia"
        And there is person with first name "Martin" and last name "Wallace"
        When I want to browse people
        Then I should see the person "Reiner Knizia"
        And I should see the person "Martin Wallace"
