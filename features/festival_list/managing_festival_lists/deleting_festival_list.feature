@managing_festival_lists
Feature: Deleting a festival list
    In order to get rid of deprecated festival lists
    As an Administrator
    I want to be able to delete festival lists

    Background:
        Given there is festival list "Essen 2017"
        And there is festival list "Cannes 2018"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a festival list
        Given I want to browse festival lists
        When I delete festival list with name "Essen 2017"
        Then I should be notified that it has been successfully deleted
        And there should not be "Essen 2017" festival list anymore
