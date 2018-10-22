@viewing_errors
Feature: Viewing 404 page
    In order to keep a good navigation
    As a visitor
    I need to be able to view a well 404 page when page I requested does not exist

    @ui
    Scenario: Viewing not found page
        When I am on not found page
        Then I should see the title "Not found page"

    @ui
    Scenario: Returning to homepage from not found page
        When I am on not found page
        Then I can return to homepage
