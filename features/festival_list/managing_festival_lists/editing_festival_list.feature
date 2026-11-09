@managing_festival_lists
Feature: Editing a festival list
    In order to change information about a festival list
    As an Administrator
    I want to be able to edit the festival list

    Background:
        Given there is festival list "Essen 2017"
        And I am a logged in administrator

    @ui
    Scenario: Renaming a festival list
        Given I want to edit festival list "Essen 2017"
        When I change its name as "Essen 2018"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And I should see the festival list named "Essen 2018" in the list
