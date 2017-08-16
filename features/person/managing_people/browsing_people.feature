@managing_people
Feature: Browsing people
    In order to see all people in the website
    As an Administrator
    I want to browse people

    Background:
        Given there is person with first name "Reiner" and last name "Knizia"
        And there is person with first name "Martin" and last name "Wallace"
        And there is person with first name "Wolfgang" and last name "Krameer"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing customers in website
        When I want to browse people
        Then there should be 3 people in the list
        And I should see the person "Reiner Knizia" in the list
