@managing_festival_lists
Feature: Browsing festival lists
    In order to see all festival lists in the website
    As an Administrator
    I want to browse festival lists

    Background:
        Given there is festival list "Essen 2017"
        And there is festival list "Cannes 2018"
        And I am a logged in administrator

    @ui
    Scenario: Browsing festival lists in website
        When I want to browse festival lists
        Then there should be 2 festival lists in the list
        And I should see the festival list "Essen 2017" in the list
