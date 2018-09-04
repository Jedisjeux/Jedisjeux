@managing_redirections
Feature: Redirections validation
    In order to avoid making mistakes when managing redirections
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Trying to add a new redirection without source
        Given I want to create a new redirection
        When I do not specify its source
        And I try to add it
        Then I should be notified that the source is required
        And this redirection should not be added
