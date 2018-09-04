@managing_redirections
Feature: Adding a new redirection
    In order to extend redirections database
    As an Administrator
    I want to add a new redirection to the website

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Adding a new redirection with source and destination
        Given I want to create a new redirection
        When I specify his source as "/url-source"
        And I specify his destination as "/url-destination"
        When I add it
        Then I should be notified that it has been successfully created
        And the redirection "/url-source" should appear in the website
